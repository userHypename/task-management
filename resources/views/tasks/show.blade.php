@extends('layouts.app')

@section('title', $task->title)

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium inline-flex items-center mb-4">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Tasks
        </a>
        <h1 class="page-title mt-3">{{ $task->title }}</h1>
    </div>

    <!-- Task Details Card -->
    <div class="max-w-3xl">
        <div class="card">
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
            <div class="mb-6">
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
            <div class="text-xs text-gray-500 mb-6">
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

    <!-- Comments & Activity -->
    <div class="max-w-3xl mt-6 space-y-6">
        <div class="bg-white border border-gray-200 rounded-md p-6">
            <h2 class="text-lg font-medium mb-4">Comments</h2>

            @if(session('success'))
                <div class="mb-4 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            @auth
                <form method="POST" action="{{ route('tasks.comments.store', $task) }}">
                    @csrf
                    <div>
                        <label for="body" class="sr-only">Add a comment</label>
                        <textarea id="body" name="body" rows="3" class="w-full border rounded-md p-2 @error('body') border-red-500 @enderror" placeholder="Write a comment...">{{ old('body') }}</textarea>
                        @error('body')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-md">Add Comment</button>
                    </div>
                </form>
            @endauth

            <div class="mt-6 space-y-4">
                @forelse($comments as $comment)
                    <div class="border rounded-md p-3">
                        <div class="flex justify-between items-start">
                            <div class="text-sm text-gray-700">{{ $comment->body }}</div>
                            <div>
                                @if(Auth::user()->id === $comment->user_id || Auth::user()->isAdmin() || Auth::user()->isManager())
                                    <form method="POST" action="{{ route('task-comments.destroy', $comment) }}" onsubmit="return confirm('Delete this comment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 mt-2">By {{ $comment->user?->name ?? 'Unknown' }} · {{ $comment->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No comments yet.</p>
                @endforelse
            </div>

            <div class="mt-4">{{ $comments->links() }}</div>
        </div>

        <div class="bg-white border border-gray-200 rounded-md p-6">
            <h2 class="text-lg font-medium mb-4">Activity Log</h2>

            <div class="space-y-3">
                @forelse($activities as $activity)
                    <div class="text-sm text-gray-700 border rounded-md p-3">
                        <div class="font-medium">{{ ucfirst(str_replace('_', ' ', $activity->type)) }}</div>
                        <div class="text-xs text-gray-500">By {{ $activity->user?->name ?? 'System' }} · {{ $activity->created_at->diffForHumans() }}</div>
                        @if($activity->note)
                            <div class="mt-2">{{ $activity->note }}</div>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No activities yet.</p>
                @endforelse
            </div>

            <div class="mt-4">{{ $activities->links() }}</div>
        </div>
    </div>
@endsection