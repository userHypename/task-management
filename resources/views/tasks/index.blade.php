@extends('layouts.app')

@section('title', 'Tasks')
@section('header-title', 'Tasks')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage and track all your tasks</p>
        </div>
        @if(auth()->user()->role === 'manager' || auth()->user()->role === 'admin')
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Task
            </a>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="q" placeholder="Search tasks by title..." value="{{ request('q') }}" 
                       class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
            </div>
            <div class="w-full sm:w-48">
                <select name="status" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter
            </button>
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset
            </a>
        </form>
    </div>

    <!-- Tasks Grid -->
    @if($tasks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($tasks as $task)
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden transition-all hover:shadow-md">
                    <!-- Priority Bar -->
                    <div class="h-1 
                        @if($task->priority == 'urgent') bg-red-600
                        @elseif($task->priority == 'high') bg-orange-500
                        @elseif($task->priority == 'medium') bg-yellow-500
                        @else bg-green-500 @endif">
                    </div>
                    
                    <div class="p-5">
                        <!-- Title -->
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-white mb-2 line-clamp-1">
                            <a href="{{ route('tasks.show', $task) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                {{ $task->title }}
                            </a>
                        </h3>
                        
                        <!-- Description -->
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2">
                            {{ $task->description ?: 'No description provided' }}
                        </p>
                        
                        <!-- Meta Info -->
                        <div class="flex items-center justify-between text-sm mb-4">
                            <div class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>
                                    @if($task->assignedUsers->count() > 0)
                                        {{ $task->assignedUsers->count() }} assigned
                                    @else
                                        Unassigned
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $task->due_date ? $task->due_date->format('M d, Y') : 'No date' }}</span>
                            </div>
                        </div>
                        
                        <!-- Status and Action -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-800">
                            <!-- Status Badge -->
                            @if($task->is_completed)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-md bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Completed
                                </span>
                            @elseif($task->status === 'in-progress')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-md bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    In Progress
                                </span>
                            @elseif($task->status === 'on-hold')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-md bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    On Hold
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Pending
                                </span>
                            @endif
                            
                            <!-- Priority Badge -->
                            @if($task->priority === 'urgent')
                                <span class="text-xs font-medium text-red-600 dark:text-red-400">Urgent</span>
                            @elseif($task->priority === 'high')
                                <span class="text-xs font-medium text-orange-600 dark:text-orange-400">High</span>
                            @elseif($task->priority === 'medium')
                                <span class="text-xs font-medium text-yellow-600 dark:text-yellow-400">Medium</span>
                            @else
                                <span class="text-xs font-medium text-green-600 dark:text-green-400">Low</span>
                            @endif
                        </div>
                        
                        <!-- View Link -->
                        <div class="mt-4 pt-2">
                            <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                                View Details
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $tasks->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">No tasks found</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Try adjusting your search or filter to find what you're looking for.</p>
            @if(auth()->user()->role === 'manager' || auth()->user()->role === 'admin')
                <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create your first task
                </a>
            @endif
        </div>
    @endif
</div>
@endsection