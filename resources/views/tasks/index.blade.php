@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tasks</h1>
                <p class="text-gray-600 mt-2">
                    @if(Auth::user()->isManager() || Auth::user()->isAdmin())
                        All tasks in the system
                    @else
                        Tasks assigned to you
                    @endif
                </p>
            </div>
            @if(Auth::user()->isManager() || Auth::user()->isAdmin())
                <a
                    href="{{ route('tasks.create') }}"
                    class="px-5 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-md hover:bg-slate-900 transition-colors"
                >
                    + Add Task
                </a>
            @endif
        </div>
    </div>

    <!-- Messages -->
    @if ($message = session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-md text-sm">
            {{ $message }}
        </div>
    @endif

    <!-- Tasks List -->
    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
        @if($tasks->count() > 0)

            <!-- Search & Filter -->
            <div class="px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <form method="GET" action="{{ route('tasks.index') }}" class="flex items-center gap-3 w-full md:w-auto">
                    <input
                        type="search"
                        name="q"
                        placeholder="Search by title"
                        value="{{ $query ?? '' }}"
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm w-full md:w-64"
                    />
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="">All</option>
                        <option value="pending" {{ (isset($status) && $status === 'pending') ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ (isset($status) && $status === 'completed') ? 'selected' : '' }}>Completed</option>
                    </select>
                    <button type="submit" class="px-3 py-2 bg-slate-800 text-white rounded-md text-sm">Filter</button>
                </form>
                <div class="ml-auto"></div>
            </div>

            <table class="w-full">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Title
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Assigned To
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Due Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Priority
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($tasks as $task)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $task->title }}</p>
                                    @if($task->description)
                                        <p class="text-sm text-gray-600 mt-1 truncate">
                                            {{ $task->description }}
                                        </p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $task->assignedTo?->name ?? 'Unassigned' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $task->due_date ? $task->due_date->format('M d') : '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex text-sm font-medium
                                    @if($task->priority === 'high')
                                        text-red-700
                                    @elseif($task->priority === 'medium')
                                        text-amber-700
                                    @else
                                        text-gray-600
                                    @endif
                                ">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($task->is_completed)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                                        ✓ Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-700">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    @if(Auth::user()->isEmployee() && $task->assigned_to === Auth::id())
                                        <!-- Employee can toggle status -->
                                        <form method="POST" action="{{ route('tasks.update', $task) }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="is_completed" value="{{ $task->is_completed ? 0 : 1 }}">
                                            <button
                                                type="submit"
                                                class="text-sm text-slate-600 hover:text-slate-900 font-medium"
                                                title="{{ $task->is_completed ? 'Mark as pending' : 'Mark as done' }}"
                                            >
                                                {{ $task->is_completed ? 'Reopen' : 'Complete' }}
                                            </button>
                                        </form>
                                    @endif

                                    @if(Auth::user()->isManager() || Auth::user()->isAdmin())
                                        <a
                                            href="{{ route('tasks.edit', $task) }}"
                                            class="text-sm text-slate-600 hover:text-slate-900 font-medium"
                                        >
                                            Edit
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route('tasks.destroy', $task) }}"
                                            class="inline"
                                            onsubmit="return confirm('Delete this task?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="text-sm text-red-600 hover:text-red-900 font-medium"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <a
                                            href="{{ route('tasks.show', $task) }}"
                                            class="text-sm text-slate-600 hover:text-slate-900 font-medium"
                                        >
                                            View
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100 bg-white">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">Showing {{ $tasks->firstItem() ?? 0 }}–{{ $tasks->lastItem() ?? 0 }} of {{ $tasks->total() }} tasks</p>
                    <div>
                        {{ $tasks->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="p-12 text-center">
                <p class="text-gray-500 text-sm">
                    @if(Auth::user()->isManager() || Auth::user()->isAdmin())
                        No tasks created yet.
                        <a href="{{ route('tasks.create') }}" class="text-slate-600 hover:text-slate-900 font-medium">
                            Create one →
                        </a>
                    @else
                        No tasks assigned to you yet.
                    @endif
                </p>
            </div>
        @endif
    </div>
@endsection
