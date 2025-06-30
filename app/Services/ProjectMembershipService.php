<?php


namespace App\Services;

use App\DTOs\ProjectMembershipDTOs\CreateProjectMembershipDTO;
use App\Enums\ProjectMembershipType;
use App\Repositories\ProjectMembershipRepository;
use App\Services\AuthService;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ProjectMembershipService
{
    public function __construct(
        private ProjectMembershipRepository $projectMembershipRepository,
        private AuthService $authService
    ) {}

    public function addMemberToProject(CreateProjectMembershipDTO $createProjectMembershipDTO): void
    {
        $loggedInUser = $this->authService->getLoggedInUser();
        if (!$loggedInUser) {
            throw new UnauthorizedException('No logged-in user found.');
        }
        // Validate invitor's membership
        $invitorMembership = $this->projectMembershipRepository->getUserMembershipInProject(
            $loggedInUser->id,
            $createProjectMembershipDTO->projectId
        );
        $invitorMembershipType = $invitorMembership->membership_type;
        if (!$invitorMembershipType->canInviteMembers()) {
            throw new UnauthorizedException('You do not have permission to add members to this project.');
        }
        $existingMembership = $this->projectMembershipRepository->getUserMembershipInProject(
            $createProjectMembershipDTO->userId,
            $createProjectMembershipDTO->projectId
        );
        if ($existingMembership) {
            throw new UnprocessableEntityHttpException('The user is already a member of this project.');
        }
        // Add the new member
        $this->projectMembershipRepository->create($createProjectMembershipDTO->toArray());
    }

    public function assignProjectOwner(int $userId, int $projectId): void
    {
        // Assign the user as an owner without any permission checks
        $this->projectMembershipRepository->create([
            'user_id' => $userId,
            'project_id' => $projectId,
            'membership_type' => ProjectMembershipType::OWNER->value,
        ]);
    }
    public function removeMemberFromProject(int $projectId, int $userId)
    {
        $loggedInUser = $this->authService->getLoggedInUser();
        if (!$loggedInUser) {
            throw new UnauthorizedException('No logged-in user found.');
        }
        if ($loggedInUser->id == $userId) {
            throw new UnprocessableEntityHttpException('Users cant remove themselvies');
        }
        $loggedInUserMembership = $this->projectMembershipRepository->getUserMembershipInProject(
            $loggedInUser->id,
            $projectId
        );
        if (!$loggedInUserMembership->membership_type->canRemoveMembers()) {
            throw new UnauthorizedException('User cant remove members');
        }
        $this->projectMembershipRepository->removeMemberFromProject($userId, $projectId);
        return "removed successfully";
    }
   public function getMembershipInProject(int $userId, int $projectId){
        return $this->projectMembershipRepository->getUserMembershipInProject(
            $userId,
            $projectId
        );
   }
}