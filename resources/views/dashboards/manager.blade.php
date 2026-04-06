@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Manager Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">
            Department: <span class="font-semibold">{{ auth()->user()->employee?->department?->name ?? 'No Department' }}</span>
        </p>
    </div>

    <!-- My Tasks Stats -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">My Tasks</h2>
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

    <!-- Department Stats -->
    @if($stats['department_tasks'] !== null)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Department Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-stat-card
                    label="Team Members"
                    :value="$stats['team_members']"
                    bgColor="bg-purple-100"
                    icon="<svg class='w-6 h-6 text-purple-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17 20h5v-2a3 3 0 00-5.856-1.487M7 20H2v-2a3 3 0 015.856-1.487M12 14a4 4 0 100-8 4 4 0 000 8zm0 0a4 4 0 015.856 1.487M12 14a4 4 0 00-5.856 1.487'></path></svg>"
                />
                <x-stat-card
                    label="Department Tasks"
                    :value="$stats['department_tasks']"
                    bgColor="bg-blue-100"
                    icon="<svg class='w-6 h-6 text-blue-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'></path></svg>"
                />
                <x-stat-card
                    label="Completed"
                    :value="$stats['dept_completed']"
                    bgColor="bg-green-100"
                    icon="<svg class='w-6 h-6 text-green-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path></svg>"
                />
                <x-stat-card
                    label="Pending"
                    :value="$stats['dept_pending']"
                    bgColor="bg-yellow-100"
                    icon="<svg class='w-6 h-6 text-yellow-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'></path></svg>"
                />
            </div>
        </div>

        <!-- Team Members Cards -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Team Members</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($team_members as $member)
                    <div class="bg-white rounded-lg shadow p-6 dark:bg-gray-800 hover:shadow-lg transition-shadow">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900 dark:text-white">
                                    {{ $member->employee?->full_name ?? $member->name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $member->employee?->position ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex gap-3 mt-4">
                            <div class="px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm font-medium">
                                {{ $member->tasks()->count() }} Tasks
                            </div>
                            <div class="px-3 py-1 rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm font-medium">
                                {{ $member->tasks()->completed()->count() }} Done
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-6 text-center text-gray-500 dark:text-gray-400 bg-white rounded-lg dark:bg-gray-800">
                        No team members in your department
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Department Tasks -->
        <div>
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
                        @forelse($department_tasks as $task)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">{{ $task->title }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $task->user->name }}</td>
                                <td class="px-6 py-4">
                                    @if($task->priority === 'high')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            🔴 High
                                        </span>
                                    @elseif($task->priority === 'medium')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            🟡 Medium
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            🟢 Low
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($task->is_completed)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            ✓ Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            ⏳ Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $task->due_date?->format('M d, Y') ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No tasks in your department
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
