<?php

namespace App\DTOs;

class ProjectInvitationDTO
{
    public function __construct(
        public string $email,
        public string $inviterName,
        public string $projectName,
        public string $inviteLink,
    ) {}
}