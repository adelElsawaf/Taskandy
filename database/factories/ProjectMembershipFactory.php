<?php

namespace Database\Factories;

use App\Enums\ProjectMembershipType;
use App\Models\ProjectMembership;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectMembershipFactory extends Factory
{
    protected $model = ProjectMembership::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'project_id' => Project::factory(), // Ensure ProjectFactory exists
            'user_id' => User::factory(),       // Ensure UserFactory exists
            'membership_type' => $this->faker->randomElement(ProjectMembershipType::values()),
            'joined_at' => now(),
        ];
    }
}