<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\TaskActivity;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isEmployee()) {
            return $this->employeeDashboard($user);
        }

        return $this->managerDashboard($user);
    }

    private function employeeDashboard($user)
    {
        // Get tasks assigned to or created by the user
        $assignedTasks = $user->assignedTasks()->with('project')->latest()->limit(4)->get();
        $createdTasks = $user->createdTasks()->with('project')->latest()->limit(4)->get();

        $stats = [
            'total' => Task::where('assigned_to', $user->id)->orWhere('created_by', $user->id)->count(),
            'completed' => Task::where('assigned_to', $user->id)->where('is_completed', true)->count(),
            'pending' => Task::where('assigned_to', $user->id)->where('status', 'pending')->count(),
            'overdue' => Task::where('assigned_to', $user->id)
                ->where('due_date', '<', now())
                ->where('is_completed', false)
                ->count(),
        ];

        return view('dashboard.employee', compact('stats', 'assignedTasks', 'createdTasks'));
    }

    private function managerDashboard($user)
    {
        // Get user's projects and associated tasks
        if (!$user->isAdmin()) {
            $projects = Project::where('manager_id', $user->id)->with('tasks')->get();
        } else {
            $projects = Project::with('tasks')->get();
        }

        $activeProjects = $projects->where('status', 'active');
        $totalProjects = $projects->count();

        // Stats
        $allTasks = collect();
        foreach ($projects as $project) {
            $allTasks = $allTasks->merge($project->tasks);
        }

        $stats = [
            'total' => $allTasks->count(),
            'completed' => $allTasks->where('is_completed', true)->count(),
            'pending' => $allTasks->where('status', 'pending')->count(),
            'overdue' => $allTasks->where('status', '!=', 'completed')
                ->filter(fn($t) => $t->due_date && $t->due_date < now())
                ->count(),
            'active_projects' => $activeProjects->count(),
            'total_projects' => $totalProjects,
        ];

        // Recent activities
        $recentActivities = TaskActivity::latest()->limit(10)->with('task', 'user')->get();
        $recentlyCompleted = Task::where('is_completed', true)
            ->whereIn('project_id', $projects->pluck('id'))
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.manager', compact('stats', 'recentActivities', 'recentlyCompleted', 'projects'));
    }
}