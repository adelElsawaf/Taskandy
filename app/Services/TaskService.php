<?php

namespace App\Services;

use App\DTOs\TaskDTO;
use App\DTOs\TaskSearchDTO;
use App\Exceptions\TaskNotFoundException;
use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskService
{
  public function __construct(private TaskRepository $taskRepository) {}

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
    $task = $this->taskRepository->create($taskDTO->toArray());
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
