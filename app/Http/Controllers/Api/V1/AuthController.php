<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends ApiController
{
    /**
     * POST /api/v1/auth/register
     * Creates a new customer account and returns a Sanctum token.
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'customer',
        ]);

        $token = $user->createToken(
            name: 'api-token',
            abilities: ['*'],
            expiresAt: now()->addDays(30)
        );

        return $this->created([
            'user'  => $this->userResource($user),
            'token' => $token->plainTextToken,
            'type'  => 'Bearer',
        ], 'Compte créé avec succès.');
    }

    /**
     * POST /api/v1/auth/login
     * Works for both SPA (session) and mobile (token).
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return $this->error('Email ou mot de passe incorrect.', 401);
        }

        /** @var User $user */
        $user = Auth::user();

        // Revoke previous tokens to enforce single session per device
        $user->tokens()->where('name', 'api-token')->delete();

        $token = $user->createToken(
            name: 'api-token',
            abilities: ['*'],
            expiresAt: now()->addDays(30)
        );

        return $this->success([
            'user'  => $this->userResource($user),
            'token' => $token->plainTextToken,
            'type'  => 'Bearer',
        ], 'Connexion réussie.');
    }

    /**
     * POST /api/v1/auth/logout
     * Revokes the current token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Déconnecté avec succès.');
    }

    /**
     * GET /api/v1/auth/me
     * Returns the currently authenticated user's profile.
     */
    public function me(Request $request): JsonResponse
    {
        return $this->success($this->userResource($request->user()));
    }

    /**
     * PATCH /api/v1/auth/profile
     * Update authenticated user profile.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name'         => ['sometimes', 'required', 'string', 'max:255'],
            'email'        => ['sometimes', 'required', 'email', 'unique:users,email,' . $user->id],
            'password'     => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'current_password' => ['required_with:password', 'string'],
        ]);

        if (! empty($data['password'])) {
            if (! Hash::check($data['current_password'], $user->password)) {
                return $this->error('Mot de passe actuel incorrect.', 422);
            }
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['current_password']);
        $user->update($data);

        return $this->success($this->userResource($user->fresh()), 'Profil mis à jour.');
    }

    /**
     * Shared user resource shape — will be replaced by UserResource in Step 7 extensions.
     */
    private function userResource(User $user): array
    {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'role'       => $user->role ?? 'customer',
            'created_at' => $user->created_at?->toISOString(),
        ];
    }
}
