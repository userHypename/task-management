<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create departments
        $engineering = Department::create([
            'name' => 'Engineering',
            'description' => 'Software development and technical infrastructure',
            'employee_count' => 0,
        ]);

        $design = Department::create([
            'name' => 'Design & Marketing',
            'description' => 'Creative team handling design and marketing',
            'employee_count' => 0,
        ]);

        $sales = Department::create([
            'name' => 'Sales',
            'description' => 'Sales and business development team',
            'employee_count' => 0,
        ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@company.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'department_id' => null,
        ]);

        // Create manager users
        $sarah = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.j@company.com',
            'password' => Hash::make('manager123'),
            'role' => 'manager',
            'department_id' => $design->id,
            'position' => 'Design Manager',
        ]);

        $michael = User::create([
            'name' => 'Michael Chen',
            'email' => 'michael.c@company.com',
            'password' => Hash::make('manager123'),
            'role' => 'manager',
            'department_id' => $engineering->id,
            'position' => 'Engineering Manager',
        ]);

        // Create employee users
        $emma = User::create([
            'name' => 'Emma Williams',
            'email' => 'emma.w@company.com',
            'password' => Hash::make('employee123'),
            'role' => 'employee',
            'department_id' => $design->id,
            'position' => 'Designer',
        ]);

        $james = User::create([
            'name' => 'James Miller',
            'email' => 'james.m@company.com',
            'password' => Hash::make('employee123'),
            'role' => 'employee',
            'department_id' => $engineering->id,
            'position' => 'Developer',
        ]);

        $olivia = User::create([
            'name' => 'Olivia Brown',
            'email' => 'olivia.b@company.com',
            'password' => Hash::make('employee123'),
            'role' => 'employee',
            'department_id' => $design->id,
            'position' => 'Marketing Specialist',
        ]);

        // Create projects
        $project1 = Project::create([
            'name' => 'Website Redesign',
            'description' => 'Complete redesign of company website with modern UI/UX',
            'manager_id' => $sarah->id,
            'status' => 'active',
            'priority' => 'high',
            'start_date' => now()->subDays(10),
            'due_date' => now()->addDays(40),
        ]);

        $project2 = Project::create([
            'name' => 'Mobile App Dev',
            'description' => 'Develop native mobile application for iOS and Android',
            'manager_id' => $michael->id,
            'status' => 'active',
            'priority' => 'urgent',
            'start_date' => now()->subDays(5),
            'due_date' => now()->addDays(75),
        ]);

        $project3 = Project::create([
            'name' => 'Marketing Campaign Q2',
            'description' => 'Q2 marketing campaign for product launch',
            'manager_id' => $sarah->id,
            'status' => 'active',
            'priority' => 'medium',
            'start_date' => now()->subDays(2),
            'due_date' => now()->addDays(40),
        ]);

        $project4 = Project::create([
            'name' => 'Internal Dashboard',
            'description' => 'Internal analytics and reporting dashboard',
            'manager_id' => $michael->id,
            'status' => 'completed',
            'priority' => 'low',
            'start_date' => now()->subDays(60),
            'due_date' => now()->subDays(1),
        ]);

        // Create tasks for projects
        Task::create([
            'title' => 'Design homepage mockups',
            'description' => 'Create high-fidelity mockups for the new website homepage',
            'project_id' => $project1->id,
            'assigned_to' => $emma->id,
            'created_by' => $sarah->id,
            'priority' => 'high',
            'status' => 'completed',
            'due_date' => now()->addDays(5),
            'is_completed' => true,
        ]);

        Task::create([
            'title' => 'Setup development environment',
            'description' => 'Configure development environment and CI/CD pipeline',
            'project_id' => $project2->id,
            'assigned_to' => $james->id,
            'created_by' => $michael->id,
            'priority' => 'urgent',
            'status' => 'in-progress',
            'due_date' => now()->addDays(3),
            'is_completed' => false,
        ]);

        Task::create([
            'title' => 'Create landing page copy',
            'description' => 'Write compelling copy for the marketing landing page',
            'project_id' => $project3->id,
            'assigned_to' => $olivia->id,
            'created_by' => $sarah->id,
            'priority' => 'medium',
            'status' => 'pending',
            'due_date' => now()->addDays(7),
            'is_completed' => false,
        ]);

        Task::create([
            'title' => 'Implement API endpoints',
            'description' => 'Build REST API endpoints for user management',
            'project_id' => $project2->id,
            'assigned_to' => $james->id,
            'created_by' => $michael->id,
            'priority' => 'high',
            'status' => 'in-progress',
            'due_date' => now()->addDays(10),
            'is_completed' => false,
        ]);

        Task::create([
            'title' => 'Frontend implementation',
            'description' => 'Implement React components based on design mockups',
            'project_id' => $project1->id,
            'assigned_to' => $emma->id,
            'created_by' => $sarah->id,
            'priority' => 'high',
            'status' => 'pending',
            'due_date' => now()->addDays(15),
            'is_completed' => false,
        ]);

        Task::create([
            'title' => 'User testing sessions',
            'description' => 'Conduct user testing sessions with target audience',
            'project_id' => $project3->id,
            'assigned_to' => $olivia->id,
            'created_by' => $sarah->id,
            'priority' => 'medium',
            'status' => 'pending',
            'due_date' => now()->addDays(20),
            'is_completed' => false,
        ]);

        Task::create([
            'title' => 'Database optimization',
            'description' => 'Optimize database queries and add indexes',
            'project_id' => $project4->id,
            'assigned_to' => $james->id,
            'created_by' => $michael->id,
            'priority' => 'low',
            'status' => 'completed',
            'due_date' => now()->subDays(5),
            'is_completed' => true,
        ]);

        Task::create([
            'title' => 'Deploy to production',
            'description' => 'Deploy dashboard to production servers',
            'project_id' => $project4->id,
            'assigned_to' => $james->id,
            'created_by' => $michael->id,
            'priority' => 'high',
            'status' => 'completed',
            'due_date' => now()->subDays(1),
            'is_completed' => true,
        ]);

        Task::create([
            'title' => 'Setup social media accounts',
            'description' => 'Create and configure social media profiles for campaign',
            'project_id' => $project3->id,
            'assigned_to' => $olivia->id,
            'created_by' => $sarah->id,
            'priority' => 'medium',
            'status' => 'on-hold',
            'due_date' => now()->addDays(12),
            'is_completed' => false,
        ]);
    }
}
