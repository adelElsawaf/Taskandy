<?php

namespace App\Services;

use App\DTOs\ProjectDTO;
use App\DTOs\ProjectSearchDTO;
use App\Repositories\ProjectRepository;
use App\Exceptions\ProjectNotFoundException;
use Illuminate\Validation\UnauthorizedException;


class ProjectService
{

    // add check of user for all projects endpoints 
    public function __construct(
        private AuthService $authService,
        private ProjectRepository $projectRepository,
        private ProjectMembershipService $projectMembershipService,
    ) {}
    public function getProjectById($id)
    {
        $project =  $this->projectRepository->getProjectById($id);
        if (!$project) {
            throw new ProjectNotFoundException("Project Doesn't Exist");
        }
        return ProjectDTO::fromModel($project);
    }
    public function getProjectByIdSecured($projectId)
    {
        $this->ensureCurrentUserCanManageProject($projectId);
        return $this->getProjectById($projectId);
    }
    public function getAllProjects(ProjectSearchDTO $searchParams)
    {
        $projects = $this->projectRepository->searchProjects($searchParams);
        return [
            'data' => $projects->map(fn($task) => ProjectDTO::fromModel($task)),
            'pagination' => [
                'total' => $projects->total(),
                'per_page' => $projects->perPage(),
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage()
            ]
        ];
    }
    public function createProject(ProjectDTO $project): ProjectDTO
    {
        // Create the project
        $projectModel = $this->projectRepository->create($project->toArray());
        // Get the logged-in user
        $loggedInUser = $this->authService->getLoggedInUser();
        if (!$loggedInUser) {
            throw new UnauthorizedException('No logged-in user found.');
        }
        // Assign the logged-in user as the project owner
        $this->projectMembershipService->assignProjectOwner($loggedInUser->id, $projectModel->id);
        $test = ProjectDTO::fromModel($projectModel);
        // Return the project DTO
        return $test;
    }

    public function updateProject(int $id, ProjectDTO $project): ProjectDTO
    {
        $this->ensureCurrentUserCanManageProject($id);
        $project = $this->projectRepository->update($id, $project->toArray());
        return ProjectDTO::fromModel($project);
    }

    private function ensureCurrentUserCanManageProject(int $project_id): void
    {
        $loggedInUser = $this->authService->getLoggedInUser();
        if (!$loggedInUser) {
            throw new UnauthorizedException('No logged-in user found.');
        }

        $membership = $this->projectMembershipService->getMembershipInProject(
            $loggedInUser->id,
            $project_id
        );

        if (!$membership || !$membership->membership_type->canManageProjects()) {
            throw new UnauthorizedException('User cannot manage this project');
        }
    }
}