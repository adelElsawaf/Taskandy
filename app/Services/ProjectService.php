<?php

namespace App\Services;

use App\DTOs\ProjectDTO;
use App\DTOs\ProjectSearchDTO;
use App\Repositories\ProjectRepository;
use App\Exceptions\ProjectNotFoundException;

class ProjectService
{
    public function __construct(
        private ProjectRepository $projectRepository,
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
    public function createProject(ProjectDTO $project)
    {
        $project = $this->projectRepository->create($project->toArray());
        return ProjectDTO::fromModel($project);
    }

    public function updateProject(int $id, ProjectDTO $project): ProjectDTO
    {
        $project = $this->projectRepository->update($id, $project->toArray());
        return ProjectDTO::fromModel($project);
    }
}