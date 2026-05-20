<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'role')) {
            $table->enum('role', ['admin', 'manager', 'employee'])->default('employee');
        }
        if (!Schema::hasColumn('users', 'department_id')) {
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
        }
        if (!Schema::hasColumn('users', 'position')) {
            $table->string('position', 100)->nullable();
        }
        if (!Schema::hasColumn('users', 'avatar')) {
            $table->string('avatar')->nullable();
        }
        if (!Schema::hasColumn('users', 'phone')) {
            $table->string('phone', 20)->nullable();
        }
        if (!Schema::hasColumn('users', 'bio')) {
            $table->text('bio')->nullable();
        }
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'bio')) {
            $table->dropColumn('bio');
        }
        if (Schema::hasColumn('users', 'phone')) {
            $table->dropColumn('phone');
        }
        if (Schema::hasColumn('users', 'avatar')) {
            $table->dropColumn('avatar');
        }
        if (Schema::hasColumn('users', 'position')) {
            $table->dropColumn('position');
        }
        if (Schema::hasColumn('users', 'department_id')) {
            $table->dropForeignKey(['department_id']);
            $table->dropColumn('department_id');
        }
        if (Schema::hasColumn('users', 'role')) {
            $table->dropColumn('role');
        }
    });
}

};
