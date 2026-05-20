@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col">
    <h1 class="text-2xl font-bold mb-4">Kanban Board</h1>
    
    <div class="flex-1 overflow-x-auto pb-4">
        <div class="flex gap-4 h-full min-w-max">
            @php
                $statuses = ['pending', 'in-progress', 'on-hold', 'completed', 'cancelled'];
                $statusColors = [
                    'pending' => 'bg-gray-100',
                    'in-progress' => 'bg-blue-50',
                    'on-hold' => 'bg-yellow-50',
                    'completed' => 'bg-green-50',
                    'cancelled' => 'bg-red-50'
                ];
            @endphp

            @foreach($statuses as $status)
            <div class="w-80 flex flex-col {{ $statusColors[$status] ?? 'bg-gray-50' }} rounded-lg shadow">
                <div class="p-3 font-bold border-b flex justify-between items-center">
                    <span>{{ strtoupper(str_replace('-', ' ', $status)) }}</span>
                    <span class="bg-white px-2 py-1 rounded text-xs">{{ $tasks[$status]->count() ?? 0 }}</span>
                </div>
                <div id="status-{{ $status }}" class="kanban-column p-2 flex-1 overflow-y-auto space-y-2">
                    @forelse($tasks[$status] ?? [] as $task)
                    <div data-id="{{ $task->id }}" class="bg-white p-3 rounded shadow cursor-move border-l-4 @if($task->priority == 'high') border-red-500 @elseif($task->priority == 'medium') border-yellow-500 @else border-green-500 @endif">
                        <h4 class="font-semibold text-sm mb-1">{{ $task->title }}</h4>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>{{ $task->assignedTo->name ?? 'Unassigned' }}</span>
                            <span>{{ $task->due_date ? $task->due_date->format('M d') : '' }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-gray-400 py-8">No tasks</div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.querySelectorAll('.kanban-column').forEach(column => {
        new Sortable(column, {
            group: 'kanban',
            animation: 150,
            onEnd: function (evt) {
                const taskId = evt.item.getAttribute('data-id');
                const newStatus = evt.to.id.replace('status-', '');
                
                fetch(`/kanban/${taskId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: newStatus })
                }).then(response => response.json())
                  .then(data => console.log('Task updated:', data))
                  .catch(error => console.error('Error:', error));
            }
        });
    });
</script>
@endsection
