<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id'             => $user->id,
            'name'           => $user->name,
            'email'          => $user->email,
            'avatar_url'     => $user->avatar_url,
            'points_balance' => $user->points_balance,
            'created_at'     => $user->created_at,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'      => 'sometimes|string|max:255',
            'avatar_url'=> 'sometimes|nullable|url',
        ]);

        $request->user()->update($data);

        return response()->json(['message' => 'Profile updated.', 'user' => $request->user()->fresh()]);
    }
}
