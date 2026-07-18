<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    private Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://api.moonshot.cn/v1/',
            'timeout'  => 30,
        ]);
    }

    public function chat(Request $request)
    {
        $data = $request->validate([
            'messages'              => 'required|array|min:1',
            'messages.*.role'       => 'required|in:user,assistant',
            'messages.*.content'    => 'required|string|max:2000',
        ]);

        // Fetch active packages for context injection
        $packages = Package::active()
            ->select(['id', 'title', 'description', 'category', 'duration_days', 'price_npr', 'location_label', 'points_reward'])
            ->get();

        $packageCatalog = $packages->map(fn($p) => [
            'id'             => $p->id,
            'title'          => $p->title,
            'description'    => $p->description,
            'category'       => $p->category,
            'duration_days'  => $p->duration_days,
            'price_npr'      => $p->price_npr,
            'location'       => $p->location_label,
            'points_reward'  => $p->points_reward,
        ])->toArray();

        $systemPrompt = $this->buildSystemPrompt($packageCatalog);

        $messages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $data['messages']
        );

        try {
            $response = $this->httpClient->post('chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.moonshot.api_key'),
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model'           => config('services.moonshot.model', 'moonshot-v1-8k'),
                    'messages'        => $messages,
                    'temperature'     => 0.7,
                    'response_format' => ['type' => 'json_object'],
                ],
            ]);

            $body    = json_decode($response->getBody()->getContents(), true);
            $content = $body['choices'][0]['message']['content'] ?? '{}';
            $parsed  = json_decode($content, true) ?? [];

            $messageText         = $parsed['message'] ?? 'Sorry, I could not process your request.';
            $recommendedIds      = $parsed['recommended_package_ids'] ?? [];

            $recommendedPackages = [];
            if (!empty($recommendedIds)) {
                $recommendedPackages = Package::whereIn('id', $recommendedIds)
                    ->active()
                    ->get()
                    ->toArray();
            }

            return response()->json([
                'message'               => $messageText,
                'recommended_packages'  => $recommendedPackages,
            ]);

        } catch (\Exception $e) {
            Log::error('Kimi API error: ' . $e->getMessage());
            return response()->json([
                'message'               => 'I\'m having trouble connecting right now. Please try again shortly.',
                'recommended_packages'  => [],
            ], 503);
        }
    }

    private function buildSystemPrompt(array $packages): string
    {
        $packageJson = json_encode($packages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
You are "Chilly", a friendly AI travel assistant for the Hilly Chilly app — Nepal's premier adventure travel platform.
Your job is to help users discover and book the perfect trekking, adventure, cultural, wildlife, or spiritual experience in Nepal.

**Your personality:**
- Warm, enthusiastic, and knowledgeable about Nepal
- Concise but informative — keep responses under 3 sentences unless more detail is needed
- Always respond in the same language the user writes in

**Available packages in the app (do NOT invent packages not in this list):**
{$packageJson}

**Response format (STRICT — always respond with valid JSON):**
{
  "message": "Your friendly response text here",
  "recommended_package_ids": [1, 2]
}

**Rules:**
1. If the user mentions a destination, activity, duration, or travel intent, detect it and recommend 1–3 relevant packages from the list above by their `id`.
2. If no packages match, set `recommended_package_ids` to an empty array [] and suggest the user explore the app.
3. Never recommend packages not in the list.
4. Always include both `message` and `recommended_package_ids` fields in JSON.
5. Keep `message` conversational and helpful.

**Few-shot examples:**

User: "I want to visit Pokhara next week for 5 days"
Response: {"message": "Pokhara is stunning! Based on your 5-day window, I found some great adventure and trekking packages that depart from Pokhara.", "recommended_package_ids": [3, 7]}

User: "I am interested in cultural experiences in Nepal"
Response: {"message": "Nepal has incredible cultural heritage! Here are some immersive cultural packages you will love.", "recommended_package_ids": [4, 9]}

User: "Hello"
Response: {"message": "Namaste! I'm Chilly, your Nepal adventure guide. Tell me where you'd like to go or what kind of adventure you're dreaming of!", "recommended_package_ids": []}
PROMPT;
    }
}
