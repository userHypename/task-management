<?php

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
        Schema::table('users', function (Blueprint $table) {
            // Add manager_id to track which manager manages this user
            $table->foreignId('manager_id')->nullable()->after('role')->constrained('users')->nullOnDelete();
            
            // Add account status for deactivation
            $table->enum('account_status', ['active', 'inactive'])->default('active')->after('manager_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['manager_id']);
            $table->dropColumn(['manager_id', 'account_status']);
        });
    }
};
