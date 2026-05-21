<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'in-progress', 'completed'])->default('pending');
            $table->text('completion_notes')->nullable();
            $table->json('submission_files')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['task_id', 'user_id']);
            
            // Indexes for performance
            $table->index(['task_id', 'user_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_assignments');
    }
};