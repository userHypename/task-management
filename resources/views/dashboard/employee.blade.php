@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded shadow border-l-4 border-blue-500">
        <h3 class="text-gray-500 text-sm">Total Tasks</h3>
        <p class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow border-l-4 border-green-500">
        <h3 class="text-gray-500 text-sm">Completed</h3>
        <p class="text-2xl font-bold">{{ $stats['completed'] ?? 0 }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow border-l-4 border-yellow-500">
        <h3 class="text-gray-500 text-sm">Pending</h3>
        <p class="text-2xl font-bold">{{ $stats['pending'] ?? 0 }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow border-l-4 border-red-500">
        <h3 class="text-gray-500 text-sm">Overdue</h3>
        <p class="text-2xl font-bold">{{ $stats['overdue'] ?? 0 }}</p>
    </div>
</div>

<div class="bg-white p-6 rounded shadow mt-6">
    <h3 class="text-lg font-semibold mb-4">Recent Tasks</h3>
    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-2">Task</th>
                <th class="py-2">Project</th>
                <th class="py-2">Status</th>
                <th class="py-2">Due Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentTasks ?? [] as $task)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-2">{{ $task->title }}</td>
                <td class="py-2">{{ $task->project->name }}</td>
                <td class="py-2"><span class="px-2 py-1 rounded-full text-xs {{ $task->status_color }}">{{ $task->status }}</span></td>
                <td class="py-2">{{ $task->due_date }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="py-4 text-center text-gray-500">No tasks found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
