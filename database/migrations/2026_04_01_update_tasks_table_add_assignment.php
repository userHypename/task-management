<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add assigned_to and created_by fields to tasks table
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Add assigned_to field only if it doesn't exist
            if (!Schema::hasColumn('tasks', 'assigned_to')) {
                $table->foreignId('assigned_to')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            }
            
            // Add created_by field only if it doesn't exist
            if (!Schema::hasColumn('tasks', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('assigned_to')->constrained('users')->onDelete('cascade');
            }
        });

        // Fill created_by with existing user_id for current tasks
        if (Schema::hasColumn('tasks', 'created_by')) {
            DB::table('tasks')->whereNull('created_by')->update(['created_by' => DB::raw('user_id')]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'assigned_to')) {
                $table->dropForeignKey('tasks_assigned_to_foreign');
                $table->dropColumn('assigned_to');
            }
            if (Schema::hasColumn('tasks', 'created_by')) {
                $table->dropForeignKey('tasks_created_by_foreign');
                $table->dropColumn('created_by');
            }
        });
    }
};
