@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Manager Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Welcome back, {{ auth()->user()->name }}!</p>
        @if(auth()->user()->department)
            <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                📁 Department: {{ auth()->user()->department->name }}
            </p>
        @endif
    </div>

    <!-- My Tasks Stats -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">My Tasks Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Tasks -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Tasks Created</p>
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
                <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-2 font-semibold">Awaiting completion</p>
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
                <p class="text-sm text-red-600 dark:text-red-400 mt-2 font-semibold">Requires attention</p>
            </div>
        </div>
    </div>

    <!-- Team Overview -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Team Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Team Members -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Team Members</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['team_members'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M7 20H2v-2a3 3 0 015.856-1.487M12 14a4 4 0 100-8 4 4 0 000 8zm0 0a4 4 0 015.856 1.487M12 14a4 4 0 00-5.856 1.487"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Projects -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Active Projects</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['active_projects'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m5.581 0a2 2 0 100-4 2 2 0 000 4zM9 7h.01M9 11h.01M9 15h.01"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Department Tasks -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Department Tasks</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['department_tasks'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Department Completed -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Department Completed</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['dept_completed'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-green-600 dark:text-green-400 mt-2">vs {{ $stats['dept_pending'] ?? 0 }} pending</p>
            </div>
        </div>
    </div>

    <!-- Team Members List -->
    @if(isset($teamMembers) && $teamMembers->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Team Members</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($teamMembers as $member)
                    <div class="bg-white rounded-lg shadow p-6 dark:bg-gray-800 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900 dark:text-white">{{ $member->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $member->position ?? 'Employee' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $member->email }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                        </div>
                        @php
                            $memberTasks = $member->assignedToManyTasks ?? collect();
                            $taskCount = $memberTasks->count();
                            $completedCount = $memberTasks->where('is_completed', true)->count();
                        @endphp
                        <div class="flex gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex-1 text-center px-3 py-2 rounded bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm font-medium">
                                📋 {{ $taskCount }} Tasks
                            </div>
                            <div class="flex-1 text-center px-3 py-2 rounded bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm font-medium">
                                ✅ {{ $completedCount }} Done
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="mb-8 bg-white rounded-lg shadow dark:bg-gray-800 p-8 text-center">
            <p class="text-gray-500 dark:text-gray-400">No team members assigned yet.</p>
            <p class="text-sm text-gray-400 mt-2">Assign employees to your department to see them here.</p>
        </div>
    @endif

    <!-- Department Tasks List -->
    @if(isset($departmentTasks) && $departmentTasks->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Department Tasks</h2>
            <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-gray-800">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Task</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Assigned To</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Priority</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Status</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Due Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($departmentTasks as $task)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-gray-900 dark:text-white font-medium hover:text-blue-600">
                                        {{ $task->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $task->assignedTo->name ?? ($task->assignedUsers->first()->name ?? 'Unassigned') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($task->priority === 'urgent')
                                        <span class="inline-flex px-2 py-1 text-xs rounded-full bg-red-600 text-white">🔥 Urgent</span>
                                    @elseif($task->priority === 'high')
                                        <span class="inline-flex px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">🔴 High</span>
                                    @elseif($task->priority === 'medium')
                                        <span class="inline-flex px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">🟡 Medium</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">🟢 Low</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($task->is_completed)
                                        <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">✅ Completed</span>
                                    @elseif($task->status === 'in-progress')
                                        <span class="inline-flex px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">🔄 In Progress</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">⏳ Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    @if($task->due_date)
                                        <span class="{{ $task->due_date->isPast() && !$task->is_completed ? 'text-red-600 font-semibold' : '' }}">
                                            {{ $task->due_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="mt-8 flex gap-4">
        <a href="{{ route('tasks.create') }}" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
            + Create New Task
        </a>
        <a href="{{ route('projects.index') }}" class="px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
            View Projects
        </a>
        <a href="{{ route('reports.index') }}" class="px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
            View Reports
        </a>
    </div>
@endsection