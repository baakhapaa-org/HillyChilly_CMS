<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTaskCompletion;
use App\Models\PackageTask;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PhotoSubmissionController extends Controller
{
    /**
     * POST /api/v1/submissions/photo
     *
     * Accepts a photo + task_description from the Flutter app, persists the
     * photo, then verifies it with Gemini Vision (gemini-1.5-flash).
     * If verified and booking_id + task_id are provided, records the
     * completion in booking_task_completions.
     *
     * Returns:
     *   { verified: true|false, message: '...' }
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'task_description' => 'required|string|max:500',
            'photo'            => 'required|file|image|max:10240', // 10 MB
            'booking_id'       => 'nullable|integer|exists:bookings,id',
            'task_id'          => 'nullable|integer|exists:package_tasks,id',
        ]);

        // ── 1. Persist the photo ────────────────────────────────────────────
        $userId = $request->user()?->id ?? 'guest';
        $path   = $request->file('photo')->store(
            "submissions/{$userId}/" . now()->format('Ymd'),
            'public'
        );

        // ── 2. Gemini Vision verification ───────────────────────────────────
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            Log::warning('[GeminiVision] GEMINI_API_KEY not set — accepting photo unconditionally.');
            $this->recordCompletion($request, $path);
            return response()->json([
                'verified' => true,
                'message'  => 'Photo saved. AI verification not configured.',
            ]);
        }

        [$verified, $message] = $this->verifyWithGemini(
            Storage::disk('public')->path($path),
            $request->task_description
        );

        // ── 3. Record completion in DB if verified ──────────────────────────
        if ($verified) {
            $this->recordCompletion($request, $path);
        }

        return response()->json([
            'verified' => $verified,
            'message'  => $message,
        ]);
    }

    // ── Private helpers ────────────────────────────────────────────────────

    /**
     * Persist a BookingTaskCompletion row when booking_id + task_id are sent.
     */
    private function recordCompletion(Request $request, string $proofPath): void
    {
        if (! $request->filled('booking_id') || ! $request->filled('task_id')) {
            return;
        }

        $booking = Booking::find($request->booking_id);
        if (! $booking || $booking->user_id !== $request->user()?->id) {
            return;
        }

        BookingTaskCompletion::firstOrCreate(
            ['booking_id' => $request->booking_id, 'task_id' => $request->task_id],
            ['proof_path' => $proofPath, 'completed_at' => now()]
        );
    }


    /**
     * Call Gemini 1.5 Flash to verify the photo matches the task description.
     *
     * Returns [bool $verified, string $message]
     */
    private function verifyWithGemini(string $filePath, string $taskDescription): array
    {
        $prompt = <<<PROMPT
You are verifying a photo submitted for a travel quest task.
Task: "{$taskDescription}"
Does this photo clearly show evidence of completing that task?
Reply with ONLY valid JSON (no markdown, no code fences):
{"verified": true, "message": "brief reason"}
or
{"verified": false, "message": "what is wrong or missing in the photo"}
PROMPT;

        try {
            $imageData = base64_encode(file_get_contents($filePath));
            $mimeType  = mime_content_type($filePath) ?: 'image/jpeg';
            $apiKey    = env('GEMINI_API_KEY');

            $response = Http::timeout(20)
                ->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}",
                    [
                        'contents'        => [[
                            'parts' => [
                                ['text' => $prompt],
                                ['inline_data' => ['mime_type' => $mimeType, 'data' => $imageData]],
                            ],
                        ]],
                        'generationConfig' => ['temperature' => 0.1],
                    ]
                );

            if (! $response->successful()) {
                Log::warning('[GeminiVision] API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                // Fail open — don't block participant on a Gemini outage.
                return [true, 'Verification service temporarily unavailable.'];
            }

            $raw = $response->json('candidates.0.content.parts.0.text', '');
            // Strip markdown code fences if Gemini wraps the JSON
            $raw    = preg_replace('/^```(?:json)?\s*|\s*```$/m', '', trim($raw));
            $result = json_decode($raw, true);

            if (! isset($result['verified'])) {
                Log::warning('[GeminiVision] Unparseable response', ['raw' => $raw]);
                return [true, 'Verification service temporarily unavailable.'];
            }

            $verified = (bool) $result['verified'];
            $message  = $result['message'] ?? ($verified ? 'Photo verified.' : "Photo doesn't match the task. Please retake.");

            Log::info('[GeminiVision] Result', [
                'task'     => $taskDescription,
                'verified' => $verified,
                'message'  => $message,
            ]);

            return [$verified, $message];

        } catch (\Throwable $e) {
            Log::error('[GeminiVision] Exception', ['error' => $e->getMessage()]);
            // Fail open — don't block participant on an error.
            return [true, 'Verification service temporarily unavailable.'];
        }
    }
}
