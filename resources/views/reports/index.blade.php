@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-8">Reports & Analytics</h1>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded shadow border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Tasks</h3>
            <p class="text-3xl font-bold">{{ $stats['total_tasks'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Completed</h3>
            <p class="text-3xl font-bold">{{ $stats['completed_tasks'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">In Progress</h3>
            <p class="text-3xl font-bold">{{ $stats['pending_tasks'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow border-l-4 border-purple-500">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Projects</h3>
            <p class="text-3xl font-bold">{{ $stats['total_projects'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tasks by Status -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-bold mb-4">Tasks by Status</h2>
            @if($tasksByStatus && $tasksByStatus->count() > 0)
            <div class="space-y-3">
                @foreach($tasksByStatus as $item)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>{{ ucfirst(str_replace('-', ' ', $item->status ?? 'Unknown')) }}</span>
                        <span class="font-semibold">{{ $item->count ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($item->count ?? 0) * 10 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500">No task data available.</p>
            @endif
        </div>

        <!-- Tasks by Priority -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-bold mb-4">Tasks by Priority</h2>
            @if($tasksByPriority && $tasksByPriority->count() > 0)
            <div class="space-y-3">
                @foreach($tasksByPriority as $item)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>
                            <span class="inline-block w-3 h-3 rounded-full mr-2 @if($item->priority == 'high') bg-red-500 @elseif($item->priority == 'medium') bg-yellow-500 @else bg-green-500 @endif"></span>
                            {{ ucfirst($item->priority ?? 'Unknown') }}
                        </span>
                        <span class="font-semibold">{{ $item->count ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="@if($item->priority == 'high') bg-red-500 @elseif($item->priority == 'medium') bg-yellow-500 @else bg-green-500 @endif h-2 rounded-full" style="width: {{ ($item->count ?? 0) * 10 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500">No priority data available.</p>
            @endif
        </div>
    </div>
</div>
@endsection
