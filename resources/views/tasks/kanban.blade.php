@extends('layouts.app')

@section('title', 'Kanban Board')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Kanban Board</h1>
        <div>
            <a href="{{ route('tasks.index') }}" class="px-3 py-1 bg-gray-100 rounded-md">Back to list</a>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .kanban-column.drag-over { background-color: #f8fafc; box-shadow: inset 0 0 0 2px rgba(99,102,241,0.06); }
        .kanban-card { cursor: grab; transition: transform 160ms ease, box-shadow 160ms ease; }
        .kanban-card.dragging { opacity: 0.6; transform: scale(0.98); box-shadow: 0 8px 20px rgba(16,24,40,0.12); }
        .kanban-placeholder { height: 64px; border: 2px dashed rgba(147,197,253,0.6); border-radius: 8px; margin: 6px 0; transition: height 120ms ease, opacity 120ms ease; }
        .kanban-placeholder.hidden { display: none; }
    </style>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- To Do -->
        <div data-column="todo" class="kanban-column" ondragover="kanbanDragOver(event)" ondrop="kanbanDrop(event)" ondragenter="kanbanDragEnter(event)" ondragleave="kanbanDragLeave(event)">
            <h2 class="font-semibold mb-2">To Do</h2>
            <div class="space-y-3 kanban-list">
                <div class="kanban-placeholder hidden" aria-hidden="true"></div>
                @forelse($todo as $task)
                    <div id="task-card-{{ $task->id }}" draggable="true" data-task-id="{{ $task->id }}" class="bg-white border rounded-md p-4 kanban-card" ondragstart="kanbanDragStart(event)" ondragend="kanbanDragEnd(event)">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-medium">{{ $task->title }}</div>
                                <div class="text-xs text-gray-500">{{ $task->project?->name ?? 'No project' }}</div>
                            </div>
                            <div class="text-right text-xs">
                                <div class="text-gray-600">{{ $task->priority }}</div>
                                <div class="text-gray-400">{{ $task->due_date?->format('M d') ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="mt-3 flex gap-2">
                            @auth
                                @if(Auth::user()->isEmployee())
                                    <button type="button" onclick="assignToMe({{ $task->id }})" class="px-2 py-1 bg-slate-800 text-white rounded text-xs">Take</button>
                                @endif

                                <button type="button" onclick="markDone({{ $task->id }})" class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Mark Done</button>

                                <a href="{{ route('tasks.show', $task) }}" class="px-2 py-1 bg-gray-100 rounded text-xs">View</a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No tasks here.</p>
                @endforelse
            </div>
        </div>

        <!-- In Progress -->
        <div data-column="in_progress" class="kanban-column" ondragover="kanbanDragOver(event)" ondrop="kanbanDrop(event)" ondragenter="kanbanDragEnter(event)" ondragleave="kanbanDragLeave(event)">
            <h2 class="font-semibold mb-2">In Progress</h2>
            <div class="space-y-3 kanban-list">
                <div class="kanban-placeholder hidden" aria-hidden="true"></div>
                @forelse($inProgress as $task)
                    <div id="task-card-{{ $task->id }}" draggable="true" data-task-id="{{ $task->id }}" class="bg-white border rounded-md p-4 kanban-card" ondragstart="kanbanDragStart(event)" ondragend="kanbanDragEnd(event)">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-medium">{{ $task->title }}</div>
                                <div class="text-xs text-gray-500">Assigned to: {{ $task->assignedTo?->name ?? '—' }}</div>
                            </div>
                            <div class="text-right text-xs">
                                <div class="text-gray-600">{{ $task->priority }}</div>
                                <div class="text-gray-400">{{ $task->due_date?->format('M d') ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="mt-3 flex gap-2">
                            @auth
                                <button type="button" onclick="markDone({{ $task->id }})" class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Mark Done</button>

                                <a href="{{ route('tasks.show', $task) }}" class="px-2 py-1 bg-gray-100 rounded text-xs">View</a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No tasks here.</p>
                @endforelse
            </div>
        </div>

        <!-- Done -->
        <div data-column="done" class="kanban-column" ondragover="kanbanDragOver(event)" ondrop="kanbanDrop(event)" ondragenter="kanbanDragEnter(event)" ondragleave="kanbanDragLeave(event)">
            <h2 class="font-semibold mb-2">Done</h2>
            <div class="space-y-3 kanban-list">
                <div class="kanban-placeholder hidden" aria-hidden="true"></div>
                @forelse($done as $task)
                    <div id="task-card-{{ $task->id }}" draggable="true" data-task-id="{{ $task->id }}" class="bg-white border rounded-md p-4 opacity-80 kanban-card" ondragstart="kanbanDragStart(event)" ondragend="kanbanDragEnd(event)">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-medium line-through">{{ $task->title }}</div>
                                <div class="text-xs text-gray-500">Completed by: {{ $task->assignedTo?->name ?? $task->createdBy?->name }}</div>
                            </div>
                            <div class="text-right text-xs">
                                <div class="text-gray-600">{{ $task->priority }}</div>
                                <div class="text-gray-400">{{ $task->due_date?->format('M d') ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('tasks.show', $task) }}" class="px-2 py-1 bg-gray-100 rounded text-xs">View</a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No tasks here.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        window.KanbanConfig = {
            csrfToken: '{{ csrf_token() }}',
            currentUserId: {{ Auth::id() ?? 'null' }}
        };

        document.addEventListener('DOMContentLoaded', function () {
            if (window.Kanban && typeof window.Kanban.init === 'function') window.Kanban.init();
        });
    </script>
@endsection
