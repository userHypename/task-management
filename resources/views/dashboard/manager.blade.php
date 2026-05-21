@extends('layouts.app')

@section('title', 'Manager Dashboard')
@section('header-title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="mb-8">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Welcome back, {{ auth()->user()->name }}!</h2>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Here's what's happening with your team today.</p>
    @if(auth()->user()->department)
        <div class="inline-flex items-center gap-2 mt-3 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m5.581 0a2 2 0 100-4 2 2 0 000 4z"/>
            </svg>
            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ auth()->user()->department->name }}</span>
        </div>
    @endif
</div>

<!-- Stats Grid - My Tasks Overview -->
<div class="mb-10">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">My Tasks Overview</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Tasks Created -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-5 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tasks Created</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['total_tasks'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-5 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $stats['completed_tasks'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-green-50 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            @php
                $completePercent = ($stats['total_tasks'] ?? 0) > 0 ? round((($stats['completed_tasks'] ?? 0) / ($stats['total_tasks'] ?? 1)) * 100) : 0;
            @endphp
            <div class="mt-3">
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-500 dark:text-gray-400">Completion rate</span>
                    <span class="text-green-600 dark:text-green-400 font-medium">{{ $completePercent }}%</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5">
                    <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $completePercent }}%"></div>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-5 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $stats['pending_tasks'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-50 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">Awaiting completion</p>
        </div>

        <!-- Overdue -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-5 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Overdue</p>
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
</div>

<!-- Team Overview Section -->
<div class="mb-10">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Team Overview</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Team Members -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Team Members</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['team_members'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-50 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Projects -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Projects</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['active_projects'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m5.581 0a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Department Tasks -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Department Tasks</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['department_tasks'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-cyan-50 dark:bg-cyan-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex justify-between text-xs">
                <span class="text-green-600 dark:text-green-400">Completed: {{ $stats['dept_completed'] ?? 0 }}</span>
                <span class="text-yellow-600 dark:text-yellow-400">Pending: {{ $stats['dept_pending'] ?? 0 }}</span>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Dept. Completion</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                        @php
                            $deptTotal = ($stats['dept_completed'] ?? 0) + ($stats['dept_pending'] ?? 0);
                            $deptPercent = $deptTotal > 0 ? round((($stats['dept_completed'] ?? 0) / $deptTotal) * 100) : 0;
                        @endphp
                        {{ $deptPercent }}%
                    </p>
                </div>
                <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $deptPercent }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Team Members Section -->
@if(isset($teamMembers) && $teamMembers->count() > 0)
<div class="mb-10">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Team Members</h3>
        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $teamMembers->count() }} members</span>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($teamMembers as $member)
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 transition-all hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-lg shadow-sm">
                    {{ substr($member->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ $member->name }}</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $member->position ?? 'Team Member' }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $member->email }}</p>
                </div>
            </div>
            @php
                $memberTasks = $member->assignedToManyTasks ?? collect();
                $taskCount = $memberTasks->count();
                $completedCount = $memberTasks->where('is_completed', true)->count();
                $memberPercent = $taskCount > 0 ? round(($completedCount / $taskCount) * 100) : 0;
            @endphp
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-500 dark:text-gray-400">Tasks: {{ $taskCount }}</span>
                    <span class="text-green-600 dark:text-green-400">{{ $memberPercent }}% complete</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5">
                    <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $memberPercent }}%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="mb-10">
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-8 text-center">
        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-gray-500 dark:text-gray-400">No team members assigned yet</p>
        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Assign employees to your department to see them here</p>
    </div>
</div>
@endif

<!-- Department Tasks Table -->
@if(isset($departmentTasks) && $departmentTasks->count() > 0)
<div class="mb-10">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Department Tasks</h3>
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Task</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Due Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($departmentTasks as $task)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('tasks.show', $task) }}" class="font-medium text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                {{ $task->title }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                            {{ $task->assignedTo->name ?? ($task->assignedUsers->first()->name ?? 'Unassigned') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($task->priority === 'urgent')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Urgent</span>
                            @elseif($task->priority === 'high')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">High</span>
                            @elseif($task->priority === 'medium')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">Medium</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Low</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($task->is_completed)
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Completed</span>
                            @elseif($task->status === 'in-progress')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">In Progress</span>
                            @elseif($task->status === 'on-hold')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400">On Hold</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($task->due_date)
                                <span class="text-sm text-gray-600 dark:text-gray-400 {{ $task->due_date->isPast() && !$task->is_completed ? 'text-red-600 dark:text-red-400 font-medium' : '' }}">
                                    {{ $task->due_date->format('M d, Y') }}
                                    @if($task->due_date->isPast() && !$task->is_completed)
                                        <span class="ml-1 text-red-600 dark:text-red-400">(Overdue)</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-sm text-gray-500 dark:text-gray-500">No date</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="flex flex-wrap gap-3">
    <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Create New Task
    </a>
    <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m5.581 0a2 2 0 100-4 2 2 0 000 4z"/>
        </svg>
        View Projects
    </a>
    <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        View Reports
    </a>
</div>
@endsection