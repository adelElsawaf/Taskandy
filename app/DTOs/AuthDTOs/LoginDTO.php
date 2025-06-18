<?php
// app/DTOs/Auth/LoginDto.php

namespace App\DTOs\AuthDTOs;

class LoginDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            );
    }
}