<?php
// app/DTOs/User/UserDto.php

namespace App\DTOs\UserDTOs;

use App\DTOs\TaskDTO;
use App\Models\User;

class UserDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly ?array $tasks = [], // An array of TaskDTO objects
        public readonly ?string $email_verified_at = null,
        public readonly ?string $created_at = null
    ) {}

    public static function fromModel(User $user, int $depth = 1): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            tasks: $depth > 0
                ? $user->assignedTasks->map(fn($task) => TaskDTO::fromModel($task, $depth - 1))->toArray()
                : null,
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
            'tasks' => array_map(
                fn($task) => method_exists($task, 'toArray') ? $task->toArray() : $task,
                $this->tasks ?? []
            ),
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
        ];
    }
}