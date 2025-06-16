<?php

namespace App\DTOs;

class ProjectSearchDTO
{
    public function __construct(
        public ?string $name = null,
        public int  $page = 1,
        public int $size = 15
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            page: $data['page'] ?? 1,
            size: $data['size'] ?? 15
        );
    }
}