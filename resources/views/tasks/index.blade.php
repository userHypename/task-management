@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="page-title">Tasks</h1>
                <p class="body-text mt-2">
                    @if(Auth::user()->isManager() || Auth::user()->isAdmin())
                        All tasks in the system
                    @else
                        Tasks assigned to you
                    @endif
                </p>
            </div>

            @if(Auth::user()->isManager() || Auth::user()->isAdmin())
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">+ Add Task</a>
            @endif
        </div>
    </div>

    @if ($message = session('success'))
        <div class="card mb-6">
            <p class="small-text text-green-700">{{ $message }}</p>
        </div>
    @endif

    <div class="space-y-3">
        @forelse($tasks as $task)
            <div class="bg-white rounded-lg shadow p-4 flex items-start gap-4 card">
                <div class="flex-shrink-0 mt-1">
                    <form method="POST" action="{{ route('tasks.update', $task) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_completed" value="{{ $task->is_completed ? 0 : 1 }}">
                        <button type="submit" class="w-5 h-5 rounded-full border flex items-center justify-center {{ $task->is_completed ? 'bg-green-500 text-white' : 'bg-white' }}">
                            @if($task->is_completed) ✓ @endif
                        </button>
                    </form>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <a href="{{ route('tasks.show', $task) }}" class="font-semibold text-text truncate">{{ $task->title }}</a>
                            @if($task->description)
                                <p class="small-text mt-1 truncate">{{ $task->description }}</p>
                            @endif
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <div class="small-text">{{ $task->due_date?->format('M d') ?? '—' }}</div>
                            <div class="mt-1">
                                @if($task->priority === 'high')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">🔴 High</span>
                                @elseif($task->priority === 'medium')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">🟡 Medium</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">🟢 Low</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center justify-between">
                        <div class="small-text text-gray-600">Assigned: {{ $task->assignedTo?->name ?? 'Unassigned' }}</div>
                        <div class="flex gap-2">
                            @if(Auth::user()->isManager() || Auth::user()->isAdmin())
                                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-ghost">Edit</a>
                                <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline" onsubmit="return confirm('Delete this task?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost text-red-600">Delete</button>
                                </form>
                            @else
                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-ghost">View</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <p class="small-text text-gray-500">
                    @if(Auth::user()->isManager() || Auth::user()->isAdmin())
                        No tasks created yet.
                        <a href="{{ route('tasks.create') }}" class="text-text font-medium">Create one →</a>
                    @else
                        No tasks assigned to you yet.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <div class="pt-4">
        <div class="flex items-center justify-between">
            <p class="small-text">Showing {{ $tasks->firstItem() ?? 0 }}–{{ $tasks->lastItem() ?? 0 }} of {{ $tasks->total() }} tasks</p>
            <div>
                {{ $tasks->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
@endsection
