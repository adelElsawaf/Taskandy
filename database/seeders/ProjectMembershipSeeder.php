<?php

namespace Database\Seeders;

use App\Models\ProjectMembership;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectMembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectMembership::factory()->count(20)->create();
    }
}