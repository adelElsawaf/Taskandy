<?php

namespace App\DTOs;

use Illuminate\Support\Carbon;
use App\Models\Project;

class ProjectDTO
{

    public function __construct(
        public string $name,
        public ?int $id = null,
        public ?Carbon $createdAt = null,
        public ?Carbon $updatedAt  = null,
    ) {}

    public static function fromModel(Project $project): self
    {
        return new self(
            name: $project->name,
            id: $project->id,
            createdAt: $project->created_at,
            updatedAt: $project->updated_at
        );
    }

    public  function toArray(): array
    {
        return [
            'name' =>  $this->name,
            "id" => $this->id,
            "created_at" => $this->createdAt,
            "updated_at" => $this->updatedAt,
        ];
    }
}