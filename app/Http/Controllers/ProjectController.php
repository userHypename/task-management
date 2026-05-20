<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $projects = Project::withCount('tasks')->get();
        } elseif ($user->isManager()) {
            $projects = Project::where('manager_id', $user->id)
                ->withCount('tasks')
                ->get();
        } else {
            // Employees see projects they are assigned to via tasks
            $projects = Project::whereHas('tasks', function ($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })->withCount('tasks')->get();
        }

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $user = auth()->user();

        // Access check
        if (!$user->isAdmin() && $project->manager_id !== $user->id && !$project->tasks()->where('assigned_to', $user->id)->exists()) {
            abort(403);
        }

        $project->load(['manager', 'tasks.assignedTo']);
        
        return view('projects.show', compact('project'));
    }
}
