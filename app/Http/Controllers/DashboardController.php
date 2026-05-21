<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard($user);
        }

        if ($user->role === 'manager') {
            return $this->managerDashboard($user);
        }

        return $this->employeeDashboard($user);
    }

    /**
     * Admin Dashboard
     */
    private function adminDashboard(User $user)
    {
        $now = Carbon::now();
        
        $totalUsers = User::count();
        $totalEmployees = User::where('role', 'employee')->count();
        $totalManagers = User::where('role', 'manager')->count();
        $totalDepartments = Department::count();
        
        $totalTasks = Task::count();
        $completedTasks = Task::where('is_completed', true)->count();
        $pendingTasks = Task::where('is_completed', false)->count();
        $overdueTasks = Task::where('due_date', '<', $now)
            ->where('is_completed', false)
            ->count();
        $highPriorityTasks = Task::where('priority', 'high')
            ->where('is_completed', false)
            ->count();

        $stats = [
            'total_users' => $totalUsers,
            'total_employees' => $totalEmployees,
            'total_managers' => $totalManagers,
            'total_departments' => $totalDepartments,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'pending_tasks' => $pendingTasks,
            'overdue_tasks' => $overdueTasks,
            'high_priority' => $highPriorityTasks,
        ];

        $departments = Department::withCount('users')->get();
        
        // FIXED: Use 'creator' relationship instead of 'user'
        $recentTasks = Task::with('creator')->latest()->limit(10)->get();

        return view('dashboard.admin', compact('stats', 'departments', 'recentTasks'));
    }

    /**
     * Manager Dashboard
     */
    private function managerDashboard(User $user)
    {
        $now = Carbon::now();
        
        $teamMembers = User::where('manager_id', $user->id)
            ->where('role', 'employee')
            ->get();
        
        if ($teamMembers->isEmpty() && $user->department_id) {
            $teamMembers = User::where('department_id', $user->department_id)
                ->where('role', 'employee')
                ->get();
        }
        
        $myTasks = Task::where('created_by', $user->id)->get();
        
        $departmentTasks = collect();
        $deptCompleted = 0;
        $deptPending = 0;
        
        if ($user->department_id) {
            $departmentUserIds = User::where('department_id', $user->department_id)
                ->where('role', 'employee')
                ->pluck('id')
                ->toArray();
            
            if (!empty($departmentUserIds)) {
                $departmentTasks = Task::whereIn('assigned_to', $departmentUserIds)
                    ->orWhereHas('assignedUsers', function($q) use ($departmentUserIds) {
                        $q->whereIn('user_id', $departmentUserIds);
                    })
                    ->with(['assignedTo', 'creator', 'assignedUsers'])
                    ->latest()
                    ->limit(20)
                    ->get();
                
                $deptCompleted = Task::whereIn('assigned_to', $departmentUserIds)
                    ->where('is_completed', true)
                    ->count();
                
                $deptPending = Task::whereIn('assigned_to', $departmentUserIds)
                    ->where('is_completed', false)
                    ->count();
            }
        }
        
        $totalTasks = $myTasks->count();
        $completedTasks = $myTasks->where('is_completed', true)->count();
        $pendingTasks = $myTasks->where('is_completed', false)->count();
        
        $overdueTasks = $myTasks->filter(function($task) use ($now) {
            return $task->due_date && Carbon::parse($task->due_date)->lt($now) && !$task->is_completed;
        })->count();
        
        $activeProjects = Project::where('manager_id', $user->id)
            ->where('status', 'active')
            ->count();
        
        $stats = [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'pending_tasks' => $pendingTasks,
            'overdue_tasks' => $overdueTasks,
            'team_members' => $teamMembers->count(),
            'active_projects' => $activeProjects,
            'department_tasks' => $departmentTasks->count(),
            'dept_completed' => $deptCompleted,
            'dept_pending' => $deptPending,
        ];
        
        $tasksByStatus = Task::where('created_by', $user->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        return view('dashboard.manager', compact('stats', 'teamMembers', 'departmentTasks', 'tasksByStatus'));
    }

    /**
     * Employee Dashboard
     */
    private function employeeDashboard(User $user)
    {
        $now = Carbon::now();
        
        $assignedTasks = Task::where('assigned_to', $user->id)
            ->orWhereHas('assignedUsers', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['project', 'creator'])
            ->latest()
            ->get();
        
        $totalTasks = $assignedTasks->count();
        $completedTasks = $assignedTasks->where('is_completed', true)->count();
        $pendingTasks = $assignedTasks->where('is_completed', false)->count();
        
        $overdueTasks = $assignedTasks->filter(function($task) use ($now) {
            return $task->due_date && Carbon::parse($task->due_date)->lt($now) && !$task->is_completed;
        })->count();
        
        $pendingTasksList = $assignedTasks->where('is_completed', false);
        $highPriority = $pendingTasksList->where('priority', 'high')->count();
        $mediumPriority = $pendingTasksList->where('priority', 'medium')->count();
        $lowPriority = $pendingTasksList->where('priority', 'low')->count();
        
        $stats = [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'pending_tasks' => $pendingTasks,
            'overdue_tasks' => $overdueTasks,
            'high_priority' => $highPriority,
            'medium_priority' => $mediumPriority,
            'low_priority' => $lowPriority,
        ];
        
        $myTasks = $assignedTasks->sortBy(function($task) {
            return $task->is_completed ? 1 : 0;
        })->sortBy('due_date')->take(10);
        
        return view('dashboard.employee', compact('stats', 'myTasks'));
    }
}