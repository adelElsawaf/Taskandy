<?php

namespace App\DTOs\ProjectMembershipDTOs;
use App\DTOs\UserDTOs\UserDto;
use App\Models\ProjectMembership;

class ProjectMembershipDTO
{
    public function __construct(
        public int $id,
        public int $projectId,
        public int $userId,
        public string $membershipType,
        public ?string $joinedAt,
        public array $user,
    ) {}

    public static function fromModel(ProjectMembership $membership): self
    {
        return new self(
            id: $membership->id,
            projectId: $membership->project_id,
            userId: $membership->user_id,
            membershipType: $membership->membership_type->value, // Cast enum to string
            joinedAt: $membership->joined_at?->toISOString(),
            user: UserDto::fromModel($membership->user)->toArray() // Access `user` as a property
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->projectId,
            'user_id' => $this->userId,
            'membership_type' => $this->membershipType,
            'joined_at' => $this->joinedAt,
            'user' => $this->user,
        ];
    }
}