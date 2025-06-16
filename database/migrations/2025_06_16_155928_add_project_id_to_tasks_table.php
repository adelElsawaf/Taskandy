<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddProjectIdToTasksTable extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable(); // Allow null values for old tasks
        });

        // Create a default project
        $defaultProjectId = DB::table('projects')->insertGetId([
            'name' => 'Default Project',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign the default project to existing tasks
        DB::table('tasks')->whereNull('project_id')->update(['project_id' => $defaultProjectId]);

        // Add foreign key after assigning default values
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['project_id']); // Drop foreign key constraint
            $table->dropColumn('project_id');    // Drop the project_id column
        });
    }
}