@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="mb-6">
        <a href="{{ route('projects.index') }}" class="text-blue-600 hover:underline">← Back to Projects</a>
    </div>

    <div class="bg-white p-6 rounded shadow mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $project->name }}</h1>
                <p class="text-gray-600">{{ $project->description }}</p>
            </div>
            <span class="text-sm px-3 py-1 rounded @if($project->status == 'active') bg-green-100 text-green-800 @elseif($project->status == 'on-hold') bg-yellow-100 text-yellow-800 @else bg-gray-100 text-gray-800 @endif">
                {{ ucfirst($project->status) }}
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t pt-4">
            <div>
                <p class="text-gray-500 text-sm">Manager</p>
                <p class="font-semibold">{{ $project->manager->name ?? 'Unassigned' }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Priority</p>
                <p class="font-semibold">{{ ucfirst($project->priority) }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Start Date</p>
                <p class="font-semibold">{{ $project->start_date ? $project->start_date->format('M d, Y') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Due Date</p>
                <p class="font-semibold">{{ $project->due_date ? $project->due_date->format('M d, Y') : 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Tasks ({{ $project->tasks->count() }})</h2>

        @if($project->tasks->isEmpty())
        <p class="text-gray-500">No tasks in this project.</p>
        @else
        <div class="space-y-3">
            @foreach($project->tasks as $task)
            <div class="border rounded p-4 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-semibold">
                        <a href="{{ route('tasks.show', $task->id) }}" class="hover:text-blue-600">
                            {{ $task->title }}
                        </a>
                    </h3>
                    <span class="text-xs px-2 py-1 rounded @if($task->status == 'completed') bg-green-100 text-green-800 @elseif($task->status == 'in-progress') bg-blue-100 text-blue-800 @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($task->description, 100) }}</p>
                <div class="flex justify-between text-xs text-gray-500">
                    <span>Assigned to: {{ $task->assignedTo->name ?? 'Unassigned' }}</span>
                    <span>Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'N/A' }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
