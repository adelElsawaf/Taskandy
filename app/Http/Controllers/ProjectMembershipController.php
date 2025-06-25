<?php

namespace App\Http\Controllers;

use App\DTOs\ProjectMembershipDTOs\CreateProjectMembershipDTO;
use App\Http\Requests\AddMembershipRequest;
use App\Http\Requests\RemoveMembershipRequest;
use App\Services\ProjectMembershipService;
use Illuminate\Http\Request;

class ProjectMembershipController extends Controller
{
    public function __construct(
        private ProjectMembershipService $projectMembershipService
    ) {}
    public function addMemberToProject(AddMembershipRequest $request)
    {
        $validated = $request->validated();

        $dto = new CreateProjectMembershipDTO(
            projectId: $validated['project_id'],
            userId: $validated['user_id'],
            membershipType: $validated['membership_type']
        );

        $response = $this->projectMembershipService->addMemberToProject($dto);

        return response()->json([
            'message' => 'Membership added successfully',
            'data' => $response,
        ], 201);
    }
    public function removeMemberFromProject(int $projectId, int $userId)
    {
        // Example Service Call
        $this->projectMembershipService->removeMemberFromProject($projectId, $userId);

        return response()->json([
            'message' => 'Member removed successfully.'
        ], 200);
    }
}