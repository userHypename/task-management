@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl">
    <div class="mb-4">
        <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:underline">← Back to Tasks</a>
    </div>
    
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h1 class="text-3xl font-bold text-gray-800">{{ $task->title }}</h1>
                <div class="flex gap-2">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold @if($task->priority == 'high') bg-red-100 text-red-800 @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif">
                        Priority: {{ ucfirst($task->priority) }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold @if($task->status == 'completed') bg-green-100 text-green-800 @elseif($task->status == 'in-progress') bg-blue-100 text-blue-800 @else bg-gray-100 text-gray-800 @endif">
                        Status: {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Description</h3>
                    <p class="text-gray-600 mt-2">{{ $task->description }}</p>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="font-bold">Project:</span> {{ $task->project->name ?? 'None' }}
                    </div>
                    <div>
                        <span class="font-bold">Due Date:</span> {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}
                    </div>
                    <div>
                        <span class="font-bold">Assigned To:</span> {{ $task->assignedTo->name ?? 'Unassigned' }}
                    </div>
                    <div>
                        <span class="font-bold">Created By:</span> {{ $task->creator->name ?? 'Unknown' }}
                    </div>
                </div>
            </div>

            <div class="flex gap-4 mt-6">
                <a href="{{ route('tasks.edit', $task) }}" class="bg-blue-500 text-white px-4 py-2 rounded font-bold hover:bg-blue-600">
                    Edit Task
                </a>
                <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure?')" class="bg-red-500 text-white px-4 py-2 rounded font-bold hover:bg-red-600">
                        Delete Task
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
