@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Projects</h1>
    </div>

    @if($projects->isEmpty())
    <div class="bg-white p-8 rounded shadow text-center text-gray-500">
        <p>No projects found.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($projects as $project)
        <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
            <div class="flex justify-between items-start mb-3">
                <h2 class="text-lg font-semibold flex-1">
                    <a href="{{ route('projects.show', $project->id) }}" class="hover:text-blue-600">
                        {{ $project->name }}
                    </a>
                </h2>
                <span class="text-xs px-2 py-1 rounded @if($project->status == 'active') bg-green-100 text-green-800 @elseif($project->status == 'on-hold') bg-yellow-100 text-yellow-800 @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($project->status) }}
                </span>
            </div>

            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($project->description, 150) }}</p>

            <div class="mb-4">
                <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
                    <span>Progress</span>
                    <span>{{ $project->tasks_count ?? 0 }} tasks</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $percentage = $project->tasks_count > 0 ? intval((($project->tasks_count ?? 0) / 10) * 100) : 0;
                    @endphp
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                </div>
            </div>

            <div class="flex justify-between items-center text-xs text-gray-500 border-t pt-3">
                <span>Manager: {{ $project->manager->name ?? 'Unassigned' }}</span>
                <span>
                    @if($project->due_date)
                        Due: {{ $project->due_date->format('M d, Y') }}
                    @else
                        No due date
                    @endif
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
