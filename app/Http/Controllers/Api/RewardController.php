<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BaakhapaaClient;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load('badges');

        return response()->json([
            'points_balance' => $user->points_balance,
            'badges'         => $user->badges->map(fn($b) => [
                'id'          => $b->id,
                'name'        => $b->name,
                'description' => $b->description,
                'icon_url'    => $b->icon_url,
                'category'    => $b->category,
                'earned_at'   => $b->pivot->earned_at,
            ]),
        ]);
    }

    public function transactions(Request $request)
    {
        $transactions = $request->user()
            ->rewardTransactions()
            ->latest()
            ->take(50)
            ->get()
            ->map(fn($t) => [
                'id'           => $t->id,
                'points_delta' => $t->points_delta,
                'reason'       => $t->reason,
                'created_at'   => $t->created_at,
            ]);

        return response()->json(['data' => $transactions]);
    }

    /**
     * Proxy the authenticated user's Baakhapaa rewards profile (coins,
     * level, rank, etc). Lazily links the account if it isn't linked yet
     * (e.g. for users created before this feature existed).
     */
    public function baakhapaaProfile(Request $request, BaakhapaaClient $baakhapaa)
    {
        $user = $request->user();

        if (!$user->baakhapaa_user_id) {
            $linked = $baakhapaa->linkAccount($user->email, $user->name);
            if ($linked) {
                $user->baakhapaa_user_id = $linked['baakhapaa_user_id'];
                $user->baakhapaa_token = $linked['access_token'];
                $user->save();
            }
        }

        $profile = $user->baakhapaa_token ? $baakhapaa->fetchProfile($user->baakhapaa_token) : null;

        return response()->json([
            'data'   => $profile,
            'linked' => (bool) $user->baakhapaa_user_id,
        ]);
    }

    /**
     * Credit Baakhapaa coins for the authenticated user (server-to-server,
     * email-based — no Baakhapaa token ever touches the client).
     */
    public function baakhapaaCredit(Request $request, BaakhapaaClient $baakhapaa)
    {
        $data = $request->validate([
            'points'  => 'required|integer|min:1',
            'source'  => 'required|string|max:100',
            'remarks' => 'required|string|max:255',
        ]);

        $user = $request->user();

        $ok = $baakhapaa->creditCoins($user->email, $data['points'], $data['source'], $data['remarks']);

        return response()->json(['success' => $ok]);
    }
}
