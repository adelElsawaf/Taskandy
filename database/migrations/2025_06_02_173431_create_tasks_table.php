<?php

use App\Enums\TaskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(false);
            $table->text('description')->nullable(); // Made nullable for flexibility
            $table->string('status')
                ->nullable(false)
                ->default(TaskStatus::NOT_STARTED->value); // Default to NOT_STARTED
            $table->date('due_date')->nullable();
            $table->timestamps();

            // Optional: Add a check constraint for status values
            // Uncomment if your database supports it
            // $table->check("status IN ('not_started', 'in_progress', 'completed')");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
