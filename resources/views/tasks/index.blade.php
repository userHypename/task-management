@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Tasks</h1>
        @if(auth()->user()->isManager() || auth()->user()->isAdmin())
            <a href="{{ route('tasks.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                + Create Task
            </a>
        @endif
    </div>
    
    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="q" placeholder="Search tasks..." value="{{ request('q') }}" 
                   class="border rounded px-3 py-2 flex-1">
            <select name="status" class="border rounded px-3 py-2">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Filter</button>
            <a href="{{ route('tasks.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Reset</a>
        </form>
    </div>
    
    <!-- Tasks Grid -->
    @if($tasks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($tasks as $task)
                <div class="bg-white rounded-lg shadow p-4 border-l-4 
                    @if($task->priority == 'urgent') border-red-600
                    @elseif($task->priority == 'high') border-orange-500
                    @elseif($task->priority == 'medium') border-yellow-500
                    @else border-green-500 @endif">
                    
                    <h3 class="font-bold text-lg mb-2">
                        <a href="{{ route('tasks.show', $task) }}" class="hover:text-blue-600">
                            {{ $task->title }}
                        </a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-2">{{ Str::limit($task->description, 100) }}</p>
                    
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">
                            @if($task->assignedUsers->count() > 0)
                                👥 {{ $task->assignedUsers->count() }} assigned
                            @else
                                📋 Unassigned
                            @endif
                        </span>
                        <span class="text-gray-500">
                            📅 {{ $task->due_date ? $task->due_date->format('M d') : 'No date' }}
                        </span>
                    </div>
                    
                    <div class="mt-3 flex justify-between items-center">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($task->status == 'completed') bg-green-100 text-green-800
                            @elseif($task->status == 'in-progress') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                        </span>
                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:underline text-sm">View →</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $tasks->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500">No tasks found.</p>
            @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                <a href="{{ route('tasks.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">Create your first task →</a>
            @endif
        </div>
    @endif
</div>
@endsection