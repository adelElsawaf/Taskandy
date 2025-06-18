<?php

namespace App\Http\Controllers;

use App\DTOs\AuthDTOs\LoginDto;
use App\DTOs\AuthDTOs\RegisterDTO;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}
    public function register(RegisterRequest $request)
    {
        try {
            $dto = new RegisterDTO(
                name: $request->input('name'),
                email: $request->input('email'),
                password: $request->input('password')
            );
            $result = $this->authService->register($dto);
            return response()->json([
                'message' => 'User registered successfully',
                'data' => $result
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 422);
        }
    }
    public function login(LoginRequest $request)
    {
        try {
            $dto = LoginDto::fromRequest($request->validated());
            $result = $this->authService->login($dto);

            return response()->json([
                'message' => 'Login successful',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 401);
        }
    }
    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request->user());

            return response()->json([
                'message' => 'Logged out successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function logoutAllDevices(Request $request)
    {
        try {
            $this->authService->logoutAllDevices($request->user());
            return response()->json([
                'message' => 'Logged out from all devices successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}