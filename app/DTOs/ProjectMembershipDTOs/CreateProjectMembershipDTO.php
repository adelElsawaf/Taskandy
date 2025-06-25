<?php

namespace App\DTOs\ProjectMembershipDTOs;

use App\Http\Requests\AddMembershipRequest;
use App\Models\ProjectMembership;

class CreateProjectMembershipDTO
{
    public function __construct(
        public int $projectId,
        public int $userId,
        public string $membershipType,
    ) {}
    public static function fromRequest(AddMembershipRequest $request): self
    {
        return new self(
            projectId: $request->projectId,
            userId: $request->userId,
            membershipType: $request->membershipType,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'project_id' => $this->projectId,
            'membership_type' => $this->membershipType,
        ];
    }
}