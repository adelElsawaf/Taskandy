<?php

namespace App\Http\Controllers;

use App\DTOs\TaskDTO;
use App\Enums\TaskStatus;
use App\Exceptions\TaskNotFoundException;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\DTOs\TaskSearchDTO;
use App\Http\Requests\AssignTaskRequest;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function getTaskById($id)
    {
        $task = $this->taskService->getTaskById($id);
        return response()->json($task);
    }
    public function getAllTasks(Request $request)
    {
        $taskSearchDTO = new TaskSearchDTO(
            title: $request->get('title'),
            due_date_before: $request->get('due_date_before'),
            due_date_after: $request->get('due_date_after'),
            page: $request->get('page', 1),
            size: $request->get('size', 15)
        );

        $tasks = $this->taskService->getAllTasks($taskSearchDTO);
        return response()->json($tasks);
    }

    public function store(TaskRequest $request)
    {
        $taskData = $request->validated();
        $taskDTO = new TaskDTO(
            title: $taskData['title'],
            description: $taskData['description'] ?? null,
            status: TaskStatus::from($taskData['status']),
            due_date: isset($taskData['due_date']) ? Carbon::parse($taskData['due_date']) : null,
            projectId: $taskData['projectId']
        );

        $task = $this->taskService->createTask($taskDTO);

        return response()->json($task->toArray(), 201);
    }
    public function update(int $id, TaskRequest $request)
    {
        $taskData = $request->validated();
        $taskDTO = new TaskDTO(
            title: $taskData['title'],
            description: $taskData['description'] ?? null,
            status: TaskStatus::from($taskData['status']),
            due_date: isset($taskData['due_date']) ? Carbon::parse($taskData['due_date']) : null,
            projectId: $taskData['projectId']

        );
        $task = $this->taskService->updateTask($id, $taskDTO);
        return response()->json($task->toArray(), 200);
    }

    public function softDelete(int $id)
    {
        return response()->json($this->taskService->softDeleteTask($id), 204);
    }
    public function hardDelete(int $id)
    {
        return response()->json($this->taskService->hardDeleteTask($id), 204);
    }
    public function assignTask(AssignTaskRequest $request)
    {
        $data = $request->validated();
        $this->taskService->assignTaskToUser($data['task_id'], $data['user_id']);
        return response()->json([
            'message' => 'Task successfully assigned to user.',
        ], 200);
    }
}