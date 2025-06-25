<?php

namespace App\Services;

use App\DTOs\ProjectDTO;
use App\DTOs\ProjectSearchDTO;
use App\Repositories\ProjectRepository;
use App\Exceptions\ProjectNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Support\Facades\Log;


class ProjectService
{
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
        $project = $this->projectRepository->update($id, $project->toArray());
        return ProjectDTO::fromModel($project);
    }
}