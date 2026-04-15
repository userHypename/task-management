@extends('layouts.app')

@section('content')
<div>
    <h1 class="page-title">Projects</h1>

    <div class="mb-4">
        <a href="{{ route('projects.create') }}" class="btn btn-primary">New Project</a>
    </div>

    <div class="card">
        <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Manager</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
            <tr>
                <td>{{ $project->name }}</td>
                <td>{{ $project->status }}</td>
                <td>{{ optional($project->manager)->name }}</td>
                <td>
                    <a href="{{ route('projects.show', $project) }}">View</a>
                    <a href="{{ route('projects.edit', $project) }}">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
        </table>

        <div class="pt-4">
            {{ $projects->links() }}
        </div>
    </div>
</div>
@endsection
