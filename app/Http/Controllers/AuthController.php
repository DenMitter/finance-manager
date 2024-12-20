<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Services\AuthService;
use Exception;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'logout', 'resetPassword', 'sendResetLinkEmail', 'updatePassword']]);
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $token = $this->authService->register($request->validated());

            return response()->json([
                'success' => true,
                'token' => $token
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $token = $this->authService->login($request->validated());

            return response()->json([
                'success' => true,
                'token' => $token
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function logout()
    {
        try {
            $this->authService->logout();

            return response()->json([
                'success' => true,
                'message' => 'Logged out'
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function resetPassword($token)
    {
        return response()->json([
            'success' => true, 'token' => $token
        ]);
    }

    public function sendResetLinkEmail(ResetPasswordRequest $request)
    {
        try {
            $this->authService->sendResetLinkEmail($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Reset password link has been sent to your email'
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $this->authService->updatePassword($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
