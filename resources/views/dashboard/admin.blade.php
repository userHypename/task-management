@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('header-title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Section -->
    <div class="mb-2">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Welcome back, {{ auth()->user()->name }}!</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Here's what's happening with your platform today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Users -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-5 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-400 dark:text-gray-500">
                {{ $stats['total_employees'] ?? 0 }} employees · {{ $stats['total_managers'] ?? 0 }} managers
            </div>
        </div>

        <!-- Departments -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-5 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Departments</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['total_departments'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-green-50 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m5.581 0a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Tasks -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-5 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tasks</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['total_tasks'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-50 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-green-600 dark:text-green-400">Completed: {{ $stats['completed_tasks'] ?? 0 }}</span>
                    <span class="text-yellow-600 dark:text-yellow-400">Pending: {{ $stats['pending_tasks'] ?? 0 }}</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5">
                    @php 
                        $total = ($stats['total_tasks'] ?? 0);
                        $completed = ($stats['completed_tasks'] ?? 0);
                        $completePercent = $total > 0 ? round(($completed / $total) * 100) : 0; 
                    @endphp
                    <div class="bg-green-500 h-1.5 rounded-full transition-all duration-300" style="width: {{ $completePercent }}%"></div>
                </div>
            </div>
        </div>

        <!-- Overdue Tasks -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-5 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Overdue Tasks</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $stats['overdue_tasks'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-red-50 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4v2m0 4v2M7 9h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V11a2 2 0 012-2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-red-600 dark:text-red-400 mt-3 font-medium">Requires attention</p>
        </div>
    </div>

    <!-- High Priority Tasks Section -->
    @if(isset($stats['high_priority']) && $stats['high_priority'] > 0)
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">High Priority Tasks</h3>
            <span class="text-sm text-red-600 dark:text-red-400">{{ $stats['high_priority'] }} tasks</span>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($recentTasks->where('priority', 'high')->take(5) as $task)
                <div class="px-5 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="flex-1">
                        <a href="{{ route('tasks.show', $task) }}" class="font-medium text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            {{ $task->title }}
                        </a>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Created by {{ $task->creator->name ?? 'Unknown' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">High</span>
                        @if($task->is_completed)
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Done</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Tasks Section -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Tasks</h3>
            <a href="{{ route('tasks.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">View all →</a>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden">
            @if(isset($recentTasks) && $recentTasks->count() > 0)
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($recentTasks as $task)
                    <div class="px-5 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex-1">
                            <a href="{{ route('tasks.show', $task) }}" class="font-medium text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                {{ $task->title }}
                            </a>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                Created by {{ $task->creator->name ?? 'Unknown' }}
                                @if($task->project)
                                    · Project: {{ $task->project->name }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- Priority Badge -->
                            @if($task->priority === 'urgent')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Urgent</span>
                            @elseif($task->priority === 'high')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">High</span>
                            @elseif($task->priority === 'medium')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">Medium</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Low</span>
                            @endif
                            
                            <!-- Status Badge -->
                            @if($task->is_completed)
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Done</span>
                            @elseif($task->status === 'in-progress')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">In Progress</span>
                            @elseif($task->status === 'on-hold')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">On Hold</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400">Pending</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="px-5 py-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400">No tasks yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex flex-wrap gap-3 pt-4">
        <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create New Task
        </a>
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Manage Users
        </a>
        <a href="{{ route('departments.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m5.581 0a2 2 0 100-4 2 2 0 000 4z"/>
            </svg>
            Manage Departments
        </a>
    </div>
</div>
@endsection