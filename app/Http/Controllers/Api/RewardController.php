<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}
