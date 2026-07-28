<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Server-to-server client for Baakhapaa's external-blog integration
 * endpoints. Holds the shared API key — this never reaches the mobile app.
 * All failures are logged and swallowed: Baakhapaa linking issues must never
 * block Hilly Chilly's own sign-in flow.
 */
class BaakhapaaClient
{
    private function client()
    {
        return Http::baseUrl(config('services.baakhapaa.base_url'))
            ->withHeaders([
                'X-API-Key' => config('services.baakhapaa.api_key'),
                'Accept' => 'application/json',
            ])
            ->timeout(6);
    }

    /**
     * Silently find-or-create a Baakhapaa rewards account for this email.
     * Returns ['baakhapaa_user_id' => int, 'access_token' => string] on
     * success, or null if the call failed/isn't configured. The access
     * token is meant to be stored server-side only (encrypted) and used
     * to proxy rich profile reads — it must never reach the mobile client.
     */
    public function linkAccount(string $email, ?string $name): ?array
    {
        if (!config('services.baakhapaa.api_key')) {
            return null;
        }

        try {
            $response = $this->client()->post('/api/external-blog/link-account', [
                'user_email' => $email,
                'name' => $name,
            ]);

            if (!$response->successful()) {
                Log::warning('Baakhapaa link-account failed', ['status' => $response->status()]);
                return null;
            }

            $userId = $response->json('data.baakhapaa_user_id');
            $token = $response->json('data.access_token');

            if (!$userId || !$token) {
                return null;
            }

            return ['baakhapaa_user_id' => $userId, 'access_token' => $token];
        } catch (\Throwable $e) {
            Log::warning('Baakhapaa link-account error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch the full Baakhapaa profile (coins, level, rank, images, etc.)
     * for an already-linked user via their stored access token.
     * Returns the raw `data` payload from GET /api/v2/user, or null on failure.
     */
    public function fetchProfile(string $accessToken): ?array
    {
        try {
            $response = Http::baseUrl(config('services.baakhapaa.base_url'))
                ->withToken($accessToken)
                ->acceptJson()
                ->timeout(6)
                ->get('/api/v2/user');

            if (!$response->successful()) {
                return null;
            }

            return $response->json('data');
        } catch (\Throwable $e) {
            Log::warning('Baakhapaa fetchProfile error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Credit Baakhapaa coins for a user via the existing external-blog
     * add-points endpoint (server-to-server, email-based, no user token).
     */
    public function creditCoins(string $email, int $points, string $source, string $remarks): bool
    {
        if (!config('services.baakhapaa.api_key')) {
            return false;
        }

        try {
            $response = $this->client()->post('/api/external-blog/add-points', [
                'user_email' => $email,
                'points' => $points,
                'source' => $source,
                'remarks' => $remarks,
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('Baakhapaa creditCoins error: ' . $e->getMessage());
            return false;
        }
    }
}
