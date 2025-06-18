<?php
// app/Repositories/UserRepository.php

namespace App\Repositories;

use App\Models\User;
use App\DTOs\Auth\RegisterDto;

class UserRepository
{
    public function create(array $userData): User
    {
        return User::create($userData);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }
}