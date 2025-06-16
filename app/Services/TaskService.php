<?php

namespace App\Services;

use App\DTOs\TaskDTO;
use App\DTOs\TaskSearchDTO;
use App\Exceptions\TaskNotFoundException;
use App\Repositories\TaskRepository;
use App\Services\ProjectService;

class TaskService
{
  public function __construct(
    private TaskRepository $taskRepository,
    private ProjectService $projectService,
  ) {}

  public function getTaskById(int $task_id): TaskDTO
  {
    $task = $this->taskRepository->getTaskById($task_id);
    if (!$task) {
      throw new TaskNotFoundException("Task not found", "Task with ID $task_id was not found");
    }
    return $task ? TaskDTO::fromModel($task) : null;
  }

  public function getAllTasks(TaskSearchDTO $searchParams)
  {
    $tasks = $this->taskRepository->searchTasks($searchParams);
    return [
      'data' => $tasks->map(fn($task) => TaskDTO::fromModel($task)),
      'pagination' => [
        'total' => $tasks->total(),
        'per_page' => $tasks->perPage(),
        'current_page' => $tasks->currentPage(),
        'last_page' => $tasks->lastPage()
      ]
    ];
  }



  public function createTask(TaskDTO $taskDTO): TaskDTO
  {
    // Validate the project exists
    $project = $this->projectService->getProjectById($taskDTO->projectId);

    // Prepare task data
    $taskData = $taskDTO->toArray();
    $taskData['project_id'] = $project->id; // Ensure project_id is used

    // Create the task
    $task = $this->taskRepository->create($taskData);

    // Return the task as a DTO
    return TaskDTO::fromModel($task);
  }

  public function updateTask(int $id, TaskDTO $taskDTO): TaskDTO
  {
    $task = $this->taskRepository->update($id, $taskDTO->toArray());
    return TaskDTO::fromModel($task);
  }

  public function softDeleteTask(int $id): void
  {
    $this->taskRepository->softDelete($id);
  }
  public function hardDeleteTask(int $id): void
  {
    $this->taskRepository->hardDelete($id);
  }
}