@extends('layouts.app')

@section('title', 'Pending Tasks Report')

@section('content')
    <div class="mb-6">
        <h1 class="page-title">Pending Tasks</h1>
        <p class="text-gray-600 mt-2">Tasks that are still pending</p>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Task</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Assigned To</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Project</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Priority</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Due Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $task->title }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ optional($task->assignedTo)->name ?? optional($task->user)->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ optional($task->project)->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ ucfirst($task->priority ?? 'low') }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $task->due_date?->format('M d, Y') ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No pending tasks found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $tasks->links() }}
        </div>
    </div>
@endsection
