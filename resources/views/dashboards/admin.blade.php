@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Welcome back! Here's your system overview.</p>
    </div>

    <!-- System Overview Stats -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">System Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-stat-card
                label="Total Users"
                :value="$stats['total_users']"
                bgColor="bg-blue-100"
                icon="<svg class='w-6 h-6 text-blue-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4.354a4 4 0 110 5.292M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z'></path></svg>"
            />
            <x-stat-card
                label="Departments"
                :value="$stats['total_departments']"
                bgColor="bg-green-100"
                icon="<svg class='w-6 h-6 text-green-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m5.581 0a2 2 0 100-4 2 2 0 000 4zM9 7h.01M9 11h.01M9 15h.01'></path></svg>"
            />
            <x-stat-card
                label="Total Employees"
                :value="$stats['total_employees']"
                bgColor="bg-purple-100"
                icon="<svg class='w-6 h-6 text-purple-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17 20h5v-2a3 3 0 00-5.856-1.487M7 20H2v-2a3 3 0 015.856-1.487M12 14a4 4 0 100-8 4 4 0 000 8zm0 0a4 4 0 015.856 1.487M12 14a4 4 0 00-5.856 1.487'></path></svg>"
            />
            <x-stat-card
                label="Total Tasks"
                :value="$stats['total_tasks']"
                bgColor="bg-amber-100"
                icon="<svg class='w-6 h-6 text-amber-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'></path></svg>"
            />
        </div>
    </div>

    <!-- Task Statistics -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Task Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-stat-card
                label="Completed"
                :value="$stats['completed_tasks']"
                bgColor="bg-green-100"
                icon="<svg class='w-6 h-6 text-green-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path></svg>"
                footer="<span class='text-green-600 font-semibold'>{{ $stats['total_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100) : 0 }}% Complete</span>"
            />
            <x-stat-card
                label="Pending"
                :value="$stats['pending_tasks']"
                bgColor="bg-yellow-100"
                icon="<svg class='w-6 h-6 text-yellow-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'></path></svg>"
                footer="<span class='text-yellow-600 font-semibold'>{{ $stats['total_tasks'] > 0 ? round(($stats['pending_tasks'] / $stats['total_tasks']) * 100) : 0 }}% Pending</span>"
            />
            <x-stat-card
                label="Overdue"
                :value="$stats['overdue_tasks']"
                bgColor="bg-red-100"
                icon="<svg class='w-6 h-6 text-red-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4v2m0 4v2M7 9h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V11a2 2 0 012-2z'></path></svg>"
                footer="<span class='text-red-600 font-semibold'>Requires Attention</span>"
            />
            <x-stat-card
                label="High Priority"
                :value="$stats['high_priority']"
                bgColor="bg-red-100"
                icon="<svg class='w-6 h-6 text-red-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 10V3L4 14h7v7l9-11h-7z'></path></svg>"
                footer="<span class='text-red-600 font-semibold'>Urgent Tasks</span>"
            />
        </div>
    </div>

    <!-- Department Overview -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Department Overview</h2>
        <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-gray-800">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Department</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Employees</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Tasks</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Completed</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($departments as $dept)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">{{ $dept->name }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $dept->employees->count() }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $dept->employees->flatMap->user->flatMap->tasks->count() }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ $dept->employees->flatMap->user->flatMap->tasks->where('is_completed', true)->count() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No departments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Tasks -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Recent Tasks</h2>
        <div class="bg-white rounded-lg shadow dark:bg-gray-800">
            @forelse($recent_tasks as $task)
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $task->title }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">By: <span class="font-medium">{{ $task->user->name }}</span></p>
                    </div>
                    <div class="flex gap-2 ml-4">
                        @if($task->is_completed)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                ✓ Completed
                            </span>
                        @else
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
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    <p>No tasks yet</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
