@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-medium text-gray-900 dark:text-white">Admin dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Welcome back — here's your system overview.</p>
    </div>

    {{-- ── System Overview ── --}}
    <p class="text-xs font-medium uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">System overview</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-8">

        {{-- Total Users --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/40 flex items-center justify-center mb-4">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0zM21 10a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total users</p>
            <p class="text-3xl font-medium text-gray-900 dark:text-white">{{ $stats['total_users'] ?? 0 }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                {{ $stats['total_employees'] ?? 0 }} employees · {{ $stats['total_managers'] ?? 0 }} managers
            </p>
        </div>

        {{-- Departments --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/40 flex items-center justify-center mb-4">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Departments</p>
            <p class="text-3xl font-medium text-gray-900 dark:text-white">{{ $stats['total_departments'] ?? 0 }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Active teams</p>
        </div>

        {{-- Employees --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
            <div class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-900/40 flex items-center justify-center mb-4">
                <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Employees</p>
            <p class="text-3xl font-medium text-gray-900 dark:text-white">{{ $stats['total_employees'] ?? 0 }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Across all departments</p>
        </div>

        {{-- Total Tasks --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/40 flex items-center justify-center mb-4">
                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total tasks</p>
            <p class="text-3xl font-medium text-gray-900 dark:text-white">{{ $stats['total_tasks'] ?? 0 }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">All-time task count</p>
        </div>
    </div>

    {{-- ── Task Statistics ── --}}
    <p class="text-xs font-medium uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">Task statistics</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-8">

        @php
            $total         = $stats['total_tasks'] ?? 1;
            $completePct   = $total > 0 ? round((($stats['completed_tasks'] ?? 0) / $total) * 100) : 0;
            $pendingPct    = $total > 0 ? round((($stats['pending_tasks']   ?? 0) / $total) * 100) : 0;
        @endphp

        {{-- Completed --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Completed</p>
            <p class="text-3xl font-medium text-emerald-600 dark:text-emerald-400">{{ $stats['completed_tasks'] ?? 0 }}</p>
            <div class="mt-3 h-1 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                <div class="h-full rounded-full bg-emerald-500" style="width: {{ $completePct }}%"></div>
            </div>
            <span class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/40 px-2 py-0.5 rounded-full">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                {{ $completePct }}% of total
            </span>
        </div>

        {{-- Pending --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Pending</p>
            <p class="text-3xl font-medium text-amber-600 dark:text-amber-400">{{ $stats['pending_tasks'] ?? 0 }}</p>
            <div class="mt-3 h-1 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                <div class="h-full rounded-full bg-amber-400" style="width: {{ $pendingPct }}%"></div>
            </div>
            <span class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/40 px-2 py-0.5 rounded-full">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $pendingPct }}% of total
            </span>
        </div>

        {{-- Overdue --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Overdue</p>
            <p class="text-3xl font-medium text-red-600 dark:text-red-400">{{ $stats['overdue_tasks'] ?? 0 }}</p>
            <div class="mt-3 h-1 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                <div class="h-full rounded-full bg-red-500" style="width: 100%"></div>
            </div>
            <span class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/40 px-2 py-0.5 rounded-full">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                Requires attention
            </span>
        </div>

        {{-- High Priority --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">High priority</p>
            <p class="text-3xl font-medium text-red-600 dark:text-red-400">{{ $stats['high_priority'] ?? 0 }}</p>
            <div class="mt-3 h-1 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                <div class="h-full rounded-full bg-orange-500" style="width: 100%"></div>
            </div>
            <span class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-orange-700 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/40 px-2 py-0.5 rounded-full">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Urgent tasks
            </span>
        </div>
    </div>

    {{-- ── Department Overview ── --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden mb-8">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-sm font-medium text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                </svg>
                Department overview
            </h2>
            <a href="{{ route('departments.index') }}" class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 flex items-center gap-1 transition-colors">
                View all
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Department</th>
                    <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Employees</th>
                    <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $dept)
                    <tr class="border-t border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-5 py-3.5 text-gray-900 dark:text-white font-medium">{{ $dept->name }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/40 px-2.5 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $dept->users_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-400 dark:text-gray-500">
                            {{ $dept->created_at ? $dept->created_at->format('M d, Y') : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">No departments found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Recent Tasks ── --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden mb-8">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-sm font-medium text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Recent tasks
            </h2>
            <a href="{{ route('tasks.create') }}" class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 flex items-center gap-1 transition-colors">
                View all
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @forelse($recentTasks as $task)
            <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors first:border-t-0">
                <div class="flex-1 min-w-0 mr-4">
                    <a href="{{ route('tasks.show', $task) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors truncate block">
                        {{ $task->title }}
                    </a>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ $task->creator->name ?? 'Unknown' }}
                    </p>
                </div>

                @if($task->is_completed)
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Completed
                    </span>
                @else
                    @if($task->priority === 'urgent')
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-red-600 text-white shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-white opacity-80"></span>Urgent
                        </span>
                    @elseif($task->priority === 'high')
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-900/40 text-red-700 dark:text-red-400 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>High
                        </span>
                    @elseif($task->priority === 'medium')
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Medium
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>Low
                        </span>
                    @endif
                @endif
            </div>
        @empty
            <div class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">No tasks yet</div>
        @endforelse
    </div>

    {{-- ── Quick Actions ── --}}
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('tasks.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create task
        </a>
        <a href="{{ route('users.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Manage users
        </a>
        <a href="{{ route('departments.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
            Manage departments
        </a>
    </div>
@endsection