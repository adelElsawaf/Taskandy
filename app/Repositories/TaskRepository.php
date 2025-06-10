<?php

namespace App\Repositories;

use App\DTOs\TaskSearchDTO;
use App\Models\Task;

class TaskRepository
{
    public function getTaskById($id)
    {
        $task = Task::find($id);
        return $task;
    }
    public function searchTasks(TaskSearchDTO $searchParams)
    {
        $query = Task::query();
        if ($searchParams->title) {
            $query->where("title", "like", "%" . $searchParams->title . "%");
        }

        if ($searchParams->due_date_before) {
            $query->where('due_date', '<=', $searchParams->due_date_before);
        }

        if ($searchParams->due_date_after) {
            $query->where('due_date', '>=', $searchParams->due_date_after);
        }
        return $query->paginate($searchParams->size, ['*'], 'page', $searchParams->page);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }
    public function update(int $task_id, array $data): Task
    {
        $task = Task::findOrFail($task_id);
        $task->update($data);
        return $task;
    }
    public function softDelete(int $task_id): void
    {
        $task = Task::findOrFail($task_id);
        $task->delete();
    }
    public function hardDelete(int $task_id): void
    {
        $task = Task::withTrashed()->findOrFail($task_id);
        $task->forceDelete();
    }
}
