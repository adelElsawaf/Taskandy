<?php
// app/DTOs/User/UserDto.php

namespace App\DTOs\UserDTOs;

use App\Models\User;

class UserDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $email_verified_at = null,
        public readonly ?string $created_at = null
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            email_verified_at: $user->email_verified_at?->toISOString(),
            created_at: $user->created_at?->toISOString()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
        ];
    }
}