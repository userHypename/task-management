@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Here's your personal task summary.</p>
            @if(auth()->user()->position)
                <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                    💼 Position: {{ auth()->user()->position }}
                </p>
            @endif
        </div>
    </div>

    <!-- Task Statistics -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Your Task Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Tasks -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Tasks</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_tasks'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Completed</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['completed_tasks'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                @php
                    $completePercent = ($stats['total_tasks'] ?? 0) > 0 ? round((($stats['completed_tasks'] ?? 0) / ($stats['total_tasks'] ?? 1)) * 100) : 0;
                @endphp
                <p class="text-sm text-green-600 dark:text-green-400 mt-2 font-semibold">{{ $completePercent }}% Complete</p>
            </div>

            <!-- Pending -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Pending</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_tasks'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-2 font-semibold">Needs completion</p>
            </div>

            <!-- Overdue -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Overdue</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['overdue_tasks'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7 9h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V11a2 2 0 012-2z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-red-600 dark:text-red-400 mt-2 font-semibold">⚠️ Requires attention</p>
            </div>
        </div>
    </div>

    <!-- Priority Breakdown -->
    @if(($stats['high_priority'] ?? 0) > 0 || ($stats['medium_priority'] ?? 0) > 0 || ($stats['low_priority'] ?? 0) > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Pending Tasks by Priority</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- High Priority -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">High Priority</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['high_priority'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-red-600 dark:text-red-400 mt-2 font-semibold">Do these first!</p>
                </div>

                <!-- Medium Priority -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Medium Priority</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['medium_priority'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-2">Schedule these tasks</p>
                </div>

                <!-- Low Priority -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Low Priority</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['low_priority'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-green-600 dark:text-green-400 mt-2">Can be done later</p>
                </div>
            </div>
        </div>
    @endif

    <!-- My Tasks List -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">My Tasks</h2>
        @if(isset($myTasks) && $myTasks->count() > 0)
            <div class="space-y-4">
                @foreach($myTasks as $task)
                    <div class="bg-white rounded-lg shadow p-5 hover:shadow-md transition-shadow dark:bg-gray-800">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2 flex-wrap">
                                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white">
                                        <a href="{{ route('tasks.show', $task) }}" class="hover:text-blue-600 transition-colors">
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
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">⏳ Pending</span>
                                    @endif
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                                    {{ Str::limit($task->description, 150) }}
                                </p>
                                
                                <div class="flex flex-wrap gap-4 text-xs text-gray-500 dark:text-gray-400">
                                    <span>📁 Project: <span class="font-medium">{{ $task->project->name ?? 'N/A' }}</span></span>
                                    <span>👤 Created by: <span class="font-medium">{{ $task->creator->name ?? 'N/A' }}</span></span>
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
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center dark:bg-gray-800">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 mb-2">No tasks assigned to you yet.</p>
                <p class="text-sm text-gray-400">Your manager will assign tasks to you soon. Check back later!</p>
            </div>
        @endif
    </div>

    <!-- Quick Links -->
    <div class="mt-8 flex gap-4">
        <a href="{{ route('tasks.index') }}" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
            View All Tasks
        </a>
        <a href="{{ route('my-tasks') }}" class="px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
            My Tasks List
        </a>
    </div>
@endsection