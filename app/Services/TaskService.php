<?php

namespace App\Services;

use App\DTOs\TaskDTO;
use App\DTOs\TaskSearchDTO;
use App\Exceptions\TaskNotFoundException;
use App\Models\ProjectMembership;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\ProjectService;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskService
{
  public function __construct(
    private TaskRepository $taskRepository,
    private ProjectService $projectService,
    private ProjectMembershipService $projectMembershipService,
    private UserService $userService,
    private AuthService $authService,
  ) {}

  public function getTaskById(int $task_id)
  {
    $task = $this->taskRepository->getTaskById($task_id);
    if (!$task) {
      throw new TaskNotFoundException("Task not found", "Task with ID $task_id was not found");
    }
    $this->ensureCurrentUserCanManageTask($task->project_id);
    return $task ? TaskDTO::fromModel($task) : null;
  }

  public function getAllTasks(int $project_id, TaskSearchDTO $searchParams)
  {
    $this->ensureCurrentUserCanManageTask($project_id);
    $tasks = $this->taskRepository->searchTasks($project_id, $searchParams);
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
    $this->ensureCurrentUserCanManageTask($project->id);
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
    $this->ensureCurrentUserCanManageTask(project_id: $task->project->id);

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

  public function assignTaskToUser($taskId, $userId)
  {
    $user = $this->userService->getUserAsEntityById($userId);
    if (!$user) {
      throw new NotFoundHttpException("User with ID $userId not found.");
    }

    $task = $this->taskRepository->getTaskById($taskId);
    if (!$task) {
      throw new TaskNotFoundException("Task with ID $taskId not found.");
    }
    $this->ensureCurrentUserCanManageTask($task->project->id);

    $task->assigned_to = $user->id;
    $this->taskRepository->save($task);
  }
  private function ensureCurrentUserCanManageTask(int $project_id): void
  {
    $loggedInUser = $this->authService->getLoggedInUser();
    if (!$loggedInUser) {
      throw new UnauthorizedException('No logged-in user found.');
    }

    $membership = $this->projectMembershipService->getMembershipInProject(
      $loggedInUser->id,
      $project_id
    );

    if (!$membership || !$membership->membership_type->canManageTasks()) {
      throw new UnauthorizedException('User cannot manage tasks in this project.');
    }
  }
}