<?php

namespace App\DTOs;

class TaskSearchDTO
{
    public function __construct(
        public ?string $title = null,
        public ?string $status = null,
        public ?string $due_date_before = null,
        public ?string $due_date_after = null,
        public int $page = 1,
        public int $size = 15
    ) {}
    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            status: $data['status'] ?? null,
            due_date_before: $data['due_date_before'] ?? null,
            due_date_after: $data['due_date_after'] ?? null,
            page: $data['page'] ?? 1,
            size: $data['size'] ?? 15
        );
    }
}
