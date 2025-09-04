<?php

namespace App\Http\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Random\RandomException;

class AuthService
{
    protected AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $this->authRepository->getByEmail($request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = bin2hex(random_bytes(40));
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'name' => $user->name,
        ]);

    }

    public function logout(Request $request)
    {
        $user = $this->authRepository->getByEmail($request);

        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return response()->json(['message' => 'Logged out']);
    }

    /**
     * @throws RandomException
     */
    public function register(Request $request): JsonResponse
    {
        $messages = [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'Email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], $messages);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->save();

        return response()->json([
            'message' => 'User registered successfully',
            'token_type' => 'Bearer'
        ], 201);
    }
}
