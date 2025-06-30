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
    public function findByIdIncludingTasks(int $id)
    {
        return User::where('id', $id)->with("assignedTasks")->first();
    }
    public function findByEmailIncludingTasks(string $email): ?User
    {
        return User::where('email', $email)->with("assignedTasks")->first();
    }
    public function findById($id)
    {
        return User::where("id", $id)->first();
    }
}