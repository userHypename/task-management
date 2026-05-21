@extends('layouts.app')

@section('title', 'My Tasks')

@section('content')
<div class="container mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Tasks</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Tasks assigned to you</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">Total Tasks</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">Completed</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['completed'] ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">In Progress</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['in_progress'] ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">Pending</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['pending'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        @if(isset($tasks) && $tasks->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($tasks as $task)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2 flex-wrap">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        <a href="{{ route('tasks.show', $task) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ $task->title }}
                                        </a>
                                    </h3>
                                    
                                    <!-- Priority Badge -->
                                    @if($task->priority === 'urgent')
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-600 text-white">🔥 Urgent</span>
                                    @elseif($task->priority === 'high')
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">🔴 High</span>
                                    @elseif($task->priority === 'medium')
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">🟡 Medium</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">🟢 Low</span>
                                    @endif
                                    
                                    <!-- Status Badge -->
                                    @if($task->is_completed)
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">✅ Completed</span>
                                    @elseif($task->status === 'in-progress')
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">🔄 In Progress</span>
                                    @elseif($task->status === 'on-hold')
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">⏸ On Hold</span>
                                    @elseif($task->status === 'cancelled')
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">❌ Cancelled</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">⏳ Pending</span>
                                    @endif
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">
                                    {{ Str::limit($task->description, 150) }}
                                </p>
                                
                                <div class="flex flex-wrap gap-4 text-xs text-gray-500 dark:text-gray-400">
                                    <span>📁 Project: {{ $task->project->name ?? 'N/A' }}</span>
                                    <span>👤 Created by: {{ $task->creator->name ?? 'N/A' }}</span>
                                    @if($task->due_date)
                                        <span class="{{ $task->due_date->isPast() && !$task->is_completed ? 'text-red-600 font-semibold' : '' }}">
                                            📅 Due: {{ $task->due_date->format('M d, Y') }}
                                            @if($task->due_date->isPast() && !$task->is_completed)
                                                (Overdue)
                                            @endif
                                        </span>
                                    @else
                                        <span>📅 No due date</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="ml-4">
                                @if(!$task->is_completed)
                                    <a href="{{ route('tasks.show', $task) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        Update Status →
                                    </a>
                                @else
                                    <a href="{{ route('tasks.show', $task) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                        View Task
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $tasks->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 mb-4">No tasks assigned to you yet.</p>
                @if(auth()->user()->role === 'employee')
                    <p class="text-sm text-gray-400">Your manager will assign tasks to you soon.</p>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection