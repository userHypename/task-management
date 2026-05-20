@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Tasks</h1>
        <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Create Task
        </a>
    </div>

    <div class="bg-white p-4 rounded shadow mb-6">
        <form method="GET" action="{{ route('tasks.index') }}" class="flex gap-4">
            <select name="status" class="border rounded px-2 py-1">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="in-progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
            <select name="priority" class="border rounded px-2 py-1">
                <option value="">All Priorities</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
            <button type="submit" class="bg-gray-500 text-white px-4 py-1 rounded">Filter</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($tasks as $task)
        <div class="bg-white p-4 rounded shadow border-l-4 @if($task->priority == 'high') border-red-500 @elseif($task->priority == 'medium') border-yellow-500 @else border-green-500 @endif">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-semibold">
                    <a href="{{ route('tasks.show', $task->id) }}" class="hover:underline">{{ $task->title }}</a>
                </h2>
                <span class="px-2 py-1 text-xs font-bold rounded @if($task->status == 'completed') bg-green-200 text-green-800 @else bg-gray-200 text-gray-800 @endif">
                    {{ ucfirst($task->status) }}
                </span>
            </div>
            <p class="text-gray-600 mt-2 text-sm">{{ Str::limit($task->description, 100) }}</p>
            <div class="mt-4 flex justify-between items-center text-xs text-gray-500">
                <span>Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'N/A' }}</span>
                <span>Assigned to: {{ $task->assignedTo->name ?? 'Unassigned' }}</span>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center text-gray-500 py-8">
            No tasks found.
        </div>
        @endforelse
    </div>
    <div class="mt-6">
        {{ $tasks->links() }}
    </div>
</div>
@endsection
