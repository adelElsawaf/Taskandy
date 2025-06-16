<?php

namespace App\Repositories;

use App\DTOs\ProjectSearchDTO;
use App\Models\Project;

class ProjectRepository
{

    public function getProjectById($id)
    {
        $project = Project::with('tasks')->find($id);
        return $project;
    }
    public function create(array $data): Project
    {
        $project = Project::create($data);
        return $project;
    }
    public function update($projectId, array $data)
    {
        $project = Project::find($projectId);
        $project->update($data);
        return $project;
    }
    public function searchProjects(ProjectSearchDTO $searchParams)
    {
        $query = Project::query()->with('tasks');
        if ($searchParams->name) {
            $query->where("name", "like", "%" . $searchParams->name . "%");
        }
        return $query->paginate($searchParams->size, ['*'], 'page', $searchParams->page);
    }
}