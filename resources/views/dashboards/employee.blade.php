@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Here's your task summary for today.</p>
        </div>
        <a
            href="{{ route('tasks.create') }}"
            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Task
        </a>
    </div>

    <!-- Task Statistics -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Task Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-stat-card
                label="Total Tasks"
                :value="$stats['total_tasks']"
                bgColor="bg-blue-100"
                icon="<svg class='w-6 h-6 text-blue-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'></path></svg>"
            />
            <x-stat-card
                label="Completed"
                :value="$stats['completed_tasks']"
                bgColor="bg-green-100"
                icon="<svg class='w-6 h-6 text-green-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path></svg>"
            />
            <x-stat-card
                label="Pending"
                :value="$stats['pending_tasks']"
                bgColor="bg-yellow-100"
                icon="<svg class='w-6 h-6 text-yellow-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'></path></svg>"
            />
            <x-stat-card
                label="Overdue"
                :value="$stats['overdue_tasks']"
                bgColor="bg-red-100"
                icon="<svg class='w-6 h-6 text-red-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4v2m0 4v2M7 9h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V11a2 2 0 012-2z'></path></svg>"
            />
        </div>
    </div>

    <!-- Priority Breakdown -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Priority Breakdown (Pending)</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-stat-card
                label="High Priority"
                :value="$stats['high_priority']"
                bgColor="bg-red-100"
                icon="<svg class='w-6 h-6 text-red-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 10V3L4 14h7v7l9-11h-7z'></path></svg>"
                footer="<span class='text-red-600 font-semibold'>⚠️ Requires immediate attention</span>"
            />
            <x-stat-card
                label="Medium Priority"
                :value="$stats['medium_priority'] ?? 0"
                bgColor="bg-yellow-100"
                icon="<svg class='w-6 h-6 text-yellow-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path></svg>"
                footer="<span class='text-yellow-600 font-semibold'>Schedule these tasks</span>"
            />
            <x-stat-card
                label="Low Priority"
                :value="$stats['low_priority'] ?? 0"
                bgColor="bg-blue-100"
                icon="<svg class='w-6 h-6 text-blue-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2 4-4m0 0a9 9 0 11-18 0 9 9 0 0118 0z'></path></svg>"
                footer="<span class='text-blue-600 font-semibold'>Can be done later</span>"
            />
        </div>
    </div>

    <!-- My Tasks -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">My Tasks</h2>
        @if($my_tasks->count() > 0)
            <div class="space-y-3">
                @foreach($my_tasks as $task)
                    <div
                        class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow dark:bg-gray-800 flex items-start gap-4"
                    >
                        <!-- Checkbox -->
                        <div class="flex-shrink-0 mt-1">
                            <input
                                type="checkbox"
                                {{ $task->is_completed ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer"
                                disabled
                            />
                        </div>

                        <!-- Task Details -->
                        <div class="flex-1 min-w-0">
                            <p
                                class="font-semibold text-gray-900 dark:text-white {{ $task->is_completed ? 'line-through text-gray-500' : '' }}"
                            >
                                {{ $task->title }}
                            </p>
                            <p
                                class="text-sm text-gray-600 dark:text-gray-400 mt-1 {{ $task->is_completed ? 'line-through' : '' }}"
                            >
                                {{ $task->description }}
                            </p>
                        </div>

                        <!-- Status & Priority Badges -->
                        <div class="flex-shrink-0 flex flex-col gap-2 items-end">
                            <!-- Priority Badge -->
                            @if($task->priority === 'high')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200"
                                >
                                    🔴 High
                                </span>
                            @elseif($task->priority === 'medium')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200"
                                >
                                    🟡 Medium
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"
                                >
                                    🟢 Low
                                </span>
                            @endif

                            <!-- Due Date -->
                            @if($task->due_date)
                                <span
                                    class="text-xs font-medium {{ $task->due_date->isPast() && !$task->is_completed ? 'text-red-600 font-bold' : 'text-gray-600 dark:text-gray-400' }}"
                                >
                                    {{ $task->due_date->format('M d, Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center dark:bg-gray-800">
                <svg
                    class="w-16 h-16 text-gray-400 mx-auto mb-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                    ></path>
                </svg>
                <p class="text-gray-600 dark:text-gray-400 mb-4">No tasks assigned yet</p>
                <a
                    href="{{ route('tasks.create') }}"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                >
                    Create your first task
                </a>
            </div>
        @endif
    </div>
@endsection
