@extends('layouts.app')

@section('title', $task->title)

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <a href="{{ route('tasks.index') }}" class="text-slate-600 hover:text-slate-900 text-sm font-medium inline-flex items-center mb-4">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Tasks
        </a>
        <h1 class="text-3xl font-bold text-gray-900 mt-3">{{ $task->title }}</h1>
    </div>

    <!-- Task Details Card -->
    <div class="max-w-3xl">
        <div class="bg-white border border-gray-200 rounded-md p-8">
            <!-- Meta Information Row -->
            <div class="flex items-center justify-between pb-6 border-b border-gray-200 mb-6">
                <!-- Status and Priority -->
                <div class="flex items-center gap-6">
                    <!-- Status -->
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Status</p>
                        <span class="inline-flex text-sm font-medium
                            @if($task->is_completed)
                                text-green-700
                            @else
                                text-gray-500
                            @endif
                        ">
                            {{ $task->is_completed ? '✓ Done' : 'Pending' }}
                        </span>
                    </div>

                    <!-- Priority -->
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Priority</p>
                        <span class="inline-flex text-sm font-medium
                            @if($task->priority === 'high')
                                text-red-700
                            @elseif($task->priority === 'medium')
                                text-amber-700
                            @else
                                text-gray-600
                            @endif
                        ">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                </div>

                <!-- Edit/Delete Actions (Managers only) -->
                @if(Auth::user()->isManager() || Auth::user()->isAdmin())
                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route('tasks.edit', $task) }}"
                            class="text-slate-600 hover:text-slate-900 text-sm font-medium"
                        >
                            Edit
                        </a>
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                onclick="return confirm('Delete this task?')"
                                class="text-red-600 hover:text-red-700 text-sm font-medium"
                            >
                                Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Description -->
            <div class="mb-8">
                <p class="text-xs font-semibold text-gray-600 uppercase mb-3">Description</p>
                <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">
                    {{ $task->description ?? '—' }}
                </p>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-3 gap-8 pb-8 border-b border-gray-200 mb-8">
                <!-- Due Date -->
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Due Date</p>
                    <p class="text-gray-900">
                        {{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}
                    </p>
                    @if($task->due_date && $task->due_date->isPast() && !$task->is_completed)
                        <p class="text-xs text-red-600 font-medium mt-1">Overdue</p>
                    @endif
                </div>

                <!-- Assigned To -->
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Assigned To</p>
                    <p class="text-gray-900">
                        {{ $task->assignedTo?->name ?? 'Unassigned' }}
                    </p>
                </div>

                <!-- Created By -->
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Created By</p>
                    <p class="text-gray-900">
                        {{ $task->createdBy?->name ?? 'Unknown' }}
                    </p>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="text-xs text-gray-500 mb-8">
                <p>Created {{ $task->created_at->format('M d, Y') }}</p>
                <p>Last updated {{ $task->updated_at->diffForHumans() }}</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <!-- Toggle Status (Employee only, if assigned) -->
                @if(Auth::user()->isEmployee() && $task->assigned_to === Auth::id())
                    <form method="POST" action="{{ route('tasks.update', $task) }}" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_completed" value="{{ $task->is_completed ? 0 : 1 }}">
                        <button
                            type="submit"
                            class="px-4 py-2 text-sm font-medium rounded-md
                                @if($task->is_completed)
                                    bg-amber-100 text-amber-700 hover:bg-amber-200
                                @else
                                    bg-green-100 text-green-700 hover:bg-green-200
                                @endif
                                transition-colors"
                        >
                            {{ $task->is_completed ? 'Reopen Task' : 'Mark Complete' }}
                        </button>
                    </form>
                @endif

                <a
                    href="{{ route('tasks.index') }}"
                    class="px-4 py-2 text-sm font-medium bg-gray-100 text-gray-900 rounded-md hover:bg-gray-200 transition-colors"
                >
                    Back
                </a>
            </div>
        </div>
    </div>
@endsection