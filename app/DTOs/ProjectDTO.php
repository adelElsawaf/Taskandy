<?php

namespace App\DTOs;

use App\Models\Project;
use App\DTOs\ProjectMembershipDTOs\ProjectMembershipDTO;

class ProjectDTO
{
    public function __construct(
        public string $name,
        public ?int $id = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
        public array $tasks = [],
        public array $memberships = []
    ) {}

    public static function fromModel(Project $project): self
    {
        return new self(
            name: $project->name,
            id: $project->id,
            createdAt: $project->created_at?->toISOString(),
            updatedAt: $project->updated_at?->toISOString(),
            tasks: $project->tasks->map(fn($task) => TaskDTO::fromModel($task))->toArray(), // Map tasks to TaskDTO
            memberships: $project->memberships->map(fn($membership) => ProjectMembershipDTO::fromModel($membership))->toArray() // Map memberships to MembershipDTO
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'tasks' => $this->tasks,
            'memberships' => $this->memberships,
        ];
    }
}