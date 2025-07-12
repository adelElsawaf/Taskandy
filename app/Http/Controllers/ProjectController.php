<?php

namespace App\Http\Controllers;

use App\DTOs\ProjectDTO;
use App\DTOs\ProjectSearchDTO;
use App\Http\Requests\ProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(private ProjectService $projectService) {}

    public function getProjectById($id)
    {
        return  response()->json($this->projectService->getProjectByIdSecured($id), 200);
    }
    public function getAllProjectsForUser(Request $request)
    {
        $searchParams =  new ProjectSearchDTO(
            name: $request->get("name")
        );
        return response()->json($this->projectService->getAllProjectsForUser($searchParams), 200);
    }

    public function store(ProjectRequest $request)
    {
        $projectData = $request->validated();
        $projectDto = new ProjectDTO(
            name: $projectData['name'],
        );
        $project = $this->projectService->createProject($projectDto);
        return response()->json($project->toArray(), 201);
    }

    public function update(int $projectId, ProjectRequest $request)
    {
        $request->validated();
        $projectDto = new ProjectDTO(
            name: $request->get("name"),
        );
        $project = $this->projectService->updateProject($projectId, $projectDto);
        return response()->json($project->toArray(), 201);
    }
}