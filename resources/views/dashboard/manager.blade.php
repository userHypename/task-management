@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded shadow border-l-4 border-blue-500">
        <h3 class="text-gray-500 text-sm">Active Projects</h3>
        <p class="text-2xl font-bold">{{ $stats['active_projects'] ?? 0 }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow border-l-4 border-purple-500">
        <h3 class="text-gray-500 text-sm">Total Projects</h3>
        <p class="text-2xl font-bold">{{ $stats['total_projects'] ?? 0 }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow border-l-4 border-yellow-500">
        <h3 class="text-gray-500 text-sm">Open Tasks</h3>
        <p class="text-2xl font-bold">{{ $stats['open_tasks'] ?? 0 }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow border-l-4 border-green-500">
        <h3 class="text-gray-500 text-sm">Completed This Month</h3>
        <p class="text-2xl font-bold">{{ $stats['recently_completed'] ?? 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
        <div class="space-y-4">
            @forelse($recentActivity ?? [] as $activity)
                <div class="flex items-center text-sm">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                    <p><strong>{{ $activity->user->name }}</strong> {{ $activity->action }} <span class="text-gray-500">({{ $activity->created_at->diffForHumans() }})</span></p>
                </div>
            @empty
                <p class="text-gray-500">No recent activity.</p>
            @endforelse
        </div>
    </div>
    
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-lg font-semibold mb-4">Recently Completed Tasks</h3>
        <ul class="space-y-3">
            @forelse($completedTasks ?? [] as $task)
                <li class="flex justify-between items-center text-sm border-b pb-2">
                    <span>{{ $task->title }}</span>
                    <span class="text-gray-500">{{ $task->completed_at->format('M d') }}</span>
                </li>
            @empty
                <li class="text-gray-500">No tasks completed recently.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
