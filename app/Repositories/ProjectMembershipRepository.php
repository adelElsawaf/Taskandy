<?php


namespace App\Repositories;

use App\Models\ProjectMembership;

class ProjectMembershipRepository
{
    public function create(array $data)
    {
        return ProjectMembership::create($data);
    }
    public function delete(int $id)
    {
        return ProjectMembership::where("id", $id)->delete();
    }

    public function getUserMembershipInProject(int $userId, int $projectId)
    {
        return ProjectMembership::with(['user', 'project'])
            ->where('user_id', $userId)
            ->where('project_id', $projectId)
            ->first();
    }


    public function removeMemberFromProject(int $userId, int $projectId)
    {
        return ProjectMembership::where('user_id', $userId)
            ->where('project_id', $projectId) // Corrected column name to match snake_case convention
            ->delete();
    }
    public function getAllProjectMembers(int $projectId)
    {
        return ProjectMembership::where('project_id', $projectId)
            ->with('user')
            ->get()
            ->pluck('user')
            ->values();
    }
}
