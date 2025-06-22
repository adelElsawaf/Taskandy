<?php

namespace Database\Factories;

use App\Models\Task;
use App\Enums\TaskStatus;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * The name of the model this factory is for.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(8), // Generates a title with 4 words
            'description' => fake()->optional()->paragraph(), // Optional paragraph for description
            'status' => TaskStatus::IN_PROGRESS,
            'due_date' => fake()->optional()->dateTimeBetween('+1 days', '+1 month'),
            'project_id' =>Project::inRandomOrder()->first()->id,
        ];
    }
}