<?php

namespace App\DTOs;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Support\Carbon;

class TaskDTO
{
    public function __construct(
        public string $title,
        public ?string $description,
        public TaskStatus $status,
        public ?Carbon $due_date,
        public ?int $id = null,

        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    ) {}

    /**
     * Create a DTO from a Task model instance.
     */
    public static function fromModel(Task $task): self
    {
        return new self(
            title: $task->title,
            description: $task->description,
            status: $task->status,
            due_date: $task->due_date,
            id: $task->id,
            created_at: $task->created_at,
            updated_at: $task->updated_at,
        );
    }

    /**
     * Convert the DTO to an array format.
     */
    public function toArray(): array
    {
        return [
            "id" => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'due_date' => $this->due_date?->toDateString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}