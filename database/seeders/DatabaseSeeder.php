<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create manager user
        $manager = User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('manager123'),
            'role' => 'manager',
        ]);

        // Create regular employee
        $employee = User::factory()->create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('employee123'),
            'role' => 'employee',
        ]);

        // Create departments
        $itDept = \App\Models\Department::factory()->create([
            'name' => 'IT Department',
            'description' => 'Information Technology',
        ]);

        $hrDept = \App\Models\Department::factory()->create([
            'name' => 'HR Department',
            'description' => 'Human Resources',
        ]);

        // Create sample tasks for each user
        \App\Models\Task::factory(8)->create(['user_id' => $admin->id]);
        \App\Models\Task::factory(8)->create(['user_id' => $manager->id]);
        \App\Models\Task::factory(8)->create(['user_id' => $employee->id]);
    }
}
