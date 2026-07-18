<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Exchange a Firebase ID token for a Sanctum token.
     * Verifies the token via Google's public tokeninfo endpoint
     * (no Firebase Admin SDK required).
     */
    public function firebaseLogin(Request $request)
    {
        $request->validate(['firebase_token' => 'required|string']);

        $idToken = $request->input('firebase_token');

        // Verify with Google
        $resp = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $idToken,
        ]);

        if (!$resp->successful()) {
            return response()->json(['message' => 'Invalid Firebase token.'], 401);
        }

        $payload = $resp->json();

        // Ensure the token was issued for our Firebase project
        $projectId = config('services.firebase.project_id');
        if ($projectId && ($payload['aud'] ?? '') !== $projectId) {
            return response()->json(['message' => 'Token audience mismatch.'], 401);
        }

        $email    = $payload['email'] ?? null;
        $name     = $payload['name'] ?? ($payload['email'] ?? 'User');
        $avatar   = $payload['picture'] ?? null;
        $fireUid  = $payload['sub'] ?? null;

        if (!$email || !$fireUid) {
            return response()->json(['message' => 'Token missing required fields.'], 422);
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'       => $name,
                'password'   => Hash::make($fireUid . config('app.key')),
                'avatar_url' => $avatar,
            ]
        );

        // Keep avatar in sync
        if ($avatar && $user->avatar_url !== $avatar) {
            $user->update(['avatar_url' => $avatar]);
        }

        $token = $user->createToken('mobile-firebase')->plainTextToken;

        return response()->json([
            'user'  => $this->userResource($user),
            'token' => $token,
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'user'  => $this->userResource($user),
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'user'  => $this->userResource($user),
            'token' => $token,
        ]);
    }

    public function me(Request $request)
    {
        return response()->json(['user' => $this->userResource($request->user())]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }

    private function userResource(User $user): array
    {
        return [
            'id'             => $user->id,
            'name'           => $user->name,
            'email'          => $user->email,
            'avatar_url'     => $user->avatar_url,
            'points_balance' => $user->points_balance,
            'created_at'     => $user->created_at,
        ];
    }
}
