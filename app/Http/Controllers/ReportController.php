<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class ReportController extends Controller
{
    public function completed(Request $request)
    {
        $perPage = $request->input('per_page', 20);
        $project = $request->input('project_id');

        $tasks = Task::completed()->with(['assignedTo', 'project']);

        if ($project) {
            $tasks->where('project_id', $project);
        }

        $tasks = $tasks->latest()->paginate($perPage)->withQueryString();
        return view('reports.completed', compact('tasks'));
    }

    public function pending(Request $request)
    {
        $perPage = $request->input('per_page', 20);
        $project = $request->input('project_id');

        $tasks = Task::pending()->with(['assignedTo', 'project']);
        if ($project) {
            $tasks->where('project_id', $project);
        }

        $tasks = $tasks->latest()->paginate($perPage)->withQueryString();
        return view('reports.pending', compact('tasks'));
    }

    public function overdue(Request $request)
    {
        $perPage = $request->input('per_page', 20);
        $project = $request->input('project_id');

        $tasks = Task::overdue()->with(['assignedTo', 'project']);
        if ($project) {
            $tasks->where('project_id', $project);
        }

        $tasks = $tasks->latest()->paginate($perPage)->withQueryString();
        return view('reports.overdue', compact('tasks'));
    }

    public function productivity(Request $request)
    {
        $perPage = $request->input('per_page', 20);

        $users = User::withCount(['tasks as completed_count' => function($q){
            $q->completed();
        }])->orderByDesc('completed_count')->paginate($perPage)->withQueryString();

        return view('reports.productivity', compact('users'));
    }
}
