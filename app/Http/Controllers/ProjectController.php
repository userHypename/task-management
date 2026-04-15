<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $query = request()->input('q');
        $perPage = request()->input('per_page', 10);

        $projects = Project::with('manager');

        if ($query) {
            $projects->where('name', 'like', '%' . $query . '%');
        }

        $projects = $projects->latest()->paginate($perPage)->withQueryString();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $managers = User::whereIn('role', ['admin','manager'])->get();
        return view('projects.create', compact('managers'));
    }

    public function store(StoreProjectRequest $request)
    {
        Project::create($request->validated());

        return redirect()->route('projects.index')->with('success', 'Project created');
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $managers = User::whereIn('role', ['admin','manager'])->get();
        return view('projects.edit', compact('project', 'managers'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return redirect()->route('projects.index')->with('success', 'Project updated');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted');
    }
}
