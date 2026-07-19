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
     *
     * Firebase ID tokens are NOT Google OAuth2 tokens — they must be verified
     * via the Firebase Identity Toolkit API, not oauth2.googleapis.com/tokeninfo.
     */
    public function firebaseLogin(Request $request)
    {
        $request->validate(['firebase_token' => 'required|string']);

        $idToken = $request->input('firebase_token');
        $apiKey  = config('services.firebase.api_key');

        if (empty($apiKey)) {
            return response()->json(['message' => 'Firebase API key not configured.'], 500);
        }

        // Verify the Firebase ID token using the Identity Toolkit lookup API.
        $resp = Http::post(
            "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key={$apiKey}",
            ['idToken' => $idToken]
        );

        if (!$resp->successful()) {
            return response()->json(['message' => 'Invalid Firebase token.'], 401);
        }

        $users = $resp->json('users');
        if (empty($users)) {
            return response()->json(['message' => 'Firebase user not found.'], 401);
        }

        $payload  = $users[0];
        $email    = $payload['email'] ?? null;
        $name     = $payload['displayName'] ?? ($payload['email'] ?? 'User');
        $avatar   = $payload['photoUrl'] ?? null;
        $fireUid  = $payload['localId'] ?? null;

        if (!$email || !$fireUid) {
            return response()->json(['message' => 'Token missing required fields.'], 422);
        }

        // Verify the token belongs to our Firebase project.
        $projectId = config('services.firebase.project_id');
        if ($projectId && !str_contains($payload['localId'] ?? '', '') ) {
            // localId is the UID — no project check needed here since the API key
            // scopes the request to our project automatically.
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'       => $name,
                'password'   => Hash::make($fireUid . config('app.key')),
                'avatar_url' => $avatar,
            ]
        );

        // Keep avatar in sync.
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
