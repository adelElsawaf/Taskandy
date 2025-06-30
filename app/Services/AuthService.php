<?php
// app/Services/AuthService.php

namespace App\Services;

use App\DTOs\AuthDTOs\LoginDto;
use App\DTOs\AuthDTOs\LoginResponseDto;
use App\DTOs\AuthDTOs\RegisterDTO;
use App\DTOs\UserDTOs\UserDto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private UserService $userService
    ) {}
    public function register(RegisterDTO $registerDTO): array
    {
        if ($this->userService->getByEmail($registerDTO->email)) {
            throw ValidationException::withMessages([
                'email' => ['The email has already been taken.'],
            ]);
        }
        $registeredUser = $this->userService->create(data: $registerDTO->toArray());
        $token = $registeredUser->createToken('auth_token')->plainTextToken;
        $response = new LoginResponseDto(
            user: UserDto::fromModel($registeredUser)->toArray(),
            token: $token,
            tokenType: 'Bearer'
        );
        return $response->toArray();
    }

    public function login(LoginDto $loginDTO): array
    {
        $user = $this->userService->getUserWithTasksByEmail($loginDTO->email);
        if (!$user || !Hash::check($loginDTO->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        $response = new LoginResponseDto(
            user: UserDto::fromModel($user)->toArray(),
            token: $token,
            tokenType: 'bearer'
        );
        return $response->toArray();
    }

    public function logout($user): bool
    {
        $user->currentAccessToken()->delete();
        return true;
    }
    public function logoutAllDevices($user): bool
    {
        $user->tokens()->delete();
        return true;
    }
    public function getLoggedInUser(){
          return Auth::user();
    }
    
}