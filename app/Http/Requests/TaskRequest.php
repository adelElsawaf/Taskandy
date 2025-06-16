<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:' . implode(',', array_map(fn($case) => $case->value, TaskStatus::cases())),
            'due_date' => 'nullable|date|after_or_equal:today',
            'projectId' => 'required|integer|exists:projects,id',
        ];
    }
}