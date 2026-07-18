<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * Accepts a photo upload with task_id, saves it, then optionally
     * verifies it with Google Cloud Vision.
     *
     * Returns:
     *   { submitted: true, verified: true|false, labels: [...], message: '...' }
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'task_id' => 'required|exists:package_tasks,id',
            'photo'   => 'required|file|image|max:10240', // 10 MB
        ]);

        $task = PackageTask::findOrFail($request->task_id);

        // ── 1. Persist the photo ────────────────────────────────────────────
        $path = $request->file('photo')->store(
            "submissions/{$request->user()->id}/{$task->id}",
            'public'
        );

        // ── 2. AI Vision verification ───────────────────────────────────────
        $apiKey = config('services.openai.api_key');

        if (empty($apiKey)) {
            // Vision not configured — accept the photo unconditionally.
            return response()->json([
                'submitted' => true,
                'verified'  => true,
                'labels'    => [],
                'message'   => 'Photo saved. AI verification not configured.',
            ]);
        }

        [$verified, $labels, $message] = $this->verifyWithVision(
            Storage::disk('public')->path($path),
            $task
        );

        return response()->json([
            'submitted' => true,
            'verified'  => $verified,
            'labels'    => $labels,
            'message'   => $message,
        ]);
    }

    // ── Private helpers ────────────────────────────────────────────────────

    /**
     * Call OpenAI GPT-4o vision to verify the photo matches the task context.
     *
     * Returns [bool $verified, array $detectedLabels, string $message]
     */
    private function verifyWithVision(string $filePath, PackageTask $task): array
    {
        $expectedLabels = data_get($task->config, 'expectedLabels', []);

        // Build a clear prompt from expected labels, falling back to the task title.
        $context = ! empty($expectedLabels)
            ? implode(', ', $expectedLabels)
            : $task->title;

        $prompt = <<<PROMPT
You are verifying a photo submitted for a quest task.
Task description: "{$context}"

Look at the photo and decide if it genuinely shows content related to that task.
Reply with ONLY valid JSON — no extra text:
{"verified": true, "reason": "brief reason"}
or
{"verified": false, "reason": "brief reason"}
PROMPT;

        try {
            $imageData = base64_encode(file_get_contents($filePath));
            $mimeType  = mime_content_type($filePath) ?: 'image/jpeg';
            $apiKey    = config('services.openai.api_key');

            $response = Http::timeout(20)
                ->withToken($apiKey)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'      => config('services.openai.vision_model', 'gpt-4o'),
                    'max_tokens' => 100,
                    'messages'   => [[
                        'role'    => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $prompt,
                            ],
                            [
                                'type'      => 'image_url',
                                'image_url' => [
                                    'url'    => "data:{$mimeType};base64,{$imageData}",
                                    'detail' => 'low', // cheaper + faster
                                ],
                            ],
                        ],
                    ]],
                ]);

            if (! $response->successful()) {
                Log::warning('OpenAI Vision error', ['status' => $response->status(), 'body' => $response->body()]);
                return [true, [], 'Vision API unavailable — photo accepted.'];
            }

            $content = trim($response->json('choices.0.message.content', '{}'));
            // Strip markdown code fences if GPT wraps the JSON
            $content = preg_replace('/^```(?:json)?\s*/i', '', $content);
            $content = preg_replace('/\s*```$/', '', $content);

            $result   = json_decode($content, true);
            $verified = (bool) ($result['verified'] ?? true);
            $reason   = $result['reason'] ?? '';

            if (! empty($expectedLabels) && ! $verified) {
                return [
                    false,
                    $expectedLabels,
                    $reason ?: "Photo doesn't match the expected location. Please retake at the correct spot.",
                ];
            }

            return [true, $expectedLabels, $reason ?: 'Photo verified.'];

        } catch (\Throwable $e) {
            Log::error('OpenAI Vision exception', ['error' => $e->getMessage()]);
            // Fail open — don't block participant on a Vision error.
            return [true, [], 'Photo saved. Verification skipped due to an error.'];
        }
    }
}
