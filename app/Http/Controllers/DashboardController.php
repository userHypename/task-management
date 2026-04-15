<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Department;
use App\Models\User;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Admin Dashboard
        if ($user->isAdmin()) {
            return $this->adminDashboard($user);
        }

        // Manager Dashboard
        if ($user->isManager()) {
            return $this->managerDashboard($user);
        }

        // Employee Dashboard (default)
        return $this->employeeDashboard($user);
    }

    private function adminDashboard($user)
    {
        $stats = [
            'total_users' => User::count(),
            'total_departments' => Department::count(),
            'total_employees' => Employee::count(),
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::completed()->count(),
            'pending_tasks' => Task::pending()->count(),
            'overdue_tasks' => Task::where('due_date', '<', now())->pending()->count(),
            'high_priority' => Task::highPriority()->pending()->count(),
        ];

        $departments = Department::with(['employees.user.tasks'])->get();
        $perPage = request()->input('per_page', 10);
        $recent_tasks = Task::with(['assignedTo', 'project'])->latest()->paginate($perPage, ['*'], 'recent_tasks_page');

        return view('dashboards.admin', compact('stats', 'departments', 'recent_tasks'));
    }

    private function managerDashboard($user)
    {
        $stats = [
            'total_tasks' => $user->tasks()->count(),
            'completed_tasks' => $user->tasks()->completed()->count(),
            'pending_tasks' => $user->tasks()->pending()->count(),
            'overdue_tasks' => $user->tasks()->where('due_date', '<', now())->pending()->count(),
            'high_priority' => $user->tasks()->highPriority()->pending()->count(),
            'department_tasks' => null,
            'team_members' => 0,
            'dept_completed' => 0,
            'dept_pending' => 0,
        ];

        $team_members = collect();
        $department_tasks = collect();

        // If manager has a department, get department stats
        if ($user->employee && $user->employee->department) {
            $department = $user->employee->department;
            
            // Get team members (employees in same department)
            $team_members = User::whereHas('employee', function($q) use ($department) {
                $q->where('department_id', $department->id);
            })->where('id', '!=', $user->id)->get();

            // Get department tasks
            $department_tasks = Task::whereHas('user.employee', function($q) use ($department) {
                $q->where('department_id', $department->id);
            })->get();

            $stats['department_tasks'] = $department_tasks->count();
            $stats['team_members'] = $team_members->count() + 1; // Include manager
            $stats['dept_completed'] = $department_tasks->where('is_completed', true)->count();
            $stats['dept_pending'] = $department_tasks->where('is_completed', false)->count();
        }

        return view('dashboards.manager', compact('stats', 'team_members', 'department_tasks'));
    }

    private function employeeDashboard($user)
    {
        $my_tasks = $user->tasks()->latest()->get();

        $stats = [
            'total_tasks' => $user->tasks()->count(),
            'completed_tasks' => $user->tasks()->completed()->count(),
            'pending_tasks' => $user->tasks()->pending()->count(),
            'overdue_tasks' => $user->tasks()->where('due_date', '<', now())->pending()->count(),
            'high_priority' => $user->tasks()->highPriority()->pending()->count(),
            'medium_priority' => $user->tasks()->where('priority', 'medium')->pending()->count(),
            'low_priority' => $user->tasks()->where('priority', 'low')->pending()->count(),
        ];

        return view('dashboards.employee', compact('stats', 'my_tasks'));
    }
}