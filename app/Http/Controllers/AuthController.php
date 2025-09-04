<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Random\RandomException;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function  __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        return $this->authService->login($request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }

    public function register(Request $request)
    {
        try {
            return $this->authService->register($request);
        } catch (RandomException $e) {
            return response('Something went wrong during registration.', 500);
        }
    }


}
