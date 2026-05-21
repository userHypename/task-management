@extends('layouts.app')

@section('title', 'Kanban Board')
@section('header-title', 'Kanban Board')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="mb-6">
        <p class="text-sm text-gray-500 dark:text-gray-400">Drag and drop tasks to change their status</p>
    </div>
    
    <!-- Kanban Columns -->
    <div class="flex-1 overflow-x-auto pb-4">
        <div class="flex gap-5 h-full min-w-max">
            @php
                $statuses = [
                    'pending' => ['label' => 'Pending', 'color' => 'gray'],
                    'in-progress' => ['label' => 'In Progress', 'color' => 'blue'],
                    'on-hold' => ['label' => 'On Hold', 'color' => 'yellow'],
                    'completed' => ['label' => 'Completed', 'color' => 'green'],
                    'cancelled' => ['label' => 'Cancelled', 'color' => 'red']
                ];
                
                $bgColors = [
                    'gray' => 'bg-gray-50 dark:bg-gray-900/50',
                    'blue' => 'bg-blue-50 dark:bg-blue-950/30',
                    'yellow' => 'bg-yellow-50 dark:bg-yellow-950/30',
                    'green' => 'bg-green-50 dark:bg-green-950/30',
                    'red' => 'bg-red-50 dark:bg-red-950/30'
                ];
                
                $borderColors = [
                    'gray' => 'border-gray-200 dark:border-gray-700',
                    'blue' => 'border-blue-200 dark:border-blue-800',
                    'yellow' => 'border-yellow-200 dark:border-yellow-800',
                    'green' => 'border-green-200 dark:border-green-800',
                    'red' => 'border-red-200 dark:border-red-800'
                ];
                
                $headerBgColors = [
                    'gray' => 'bg-gray-100 dark:bg-gray-800',
                    'blue' => 'bg-blue-100 dark:bg-blue-900/50',
                    'yellow' => 'bg-yellow-100 dark:bg-yellow-900/50',
                    'green' => 'bg-green-100 dark:bg-green-900/50',
                    'red' => 'bg-red-100 dark:bg-red-900/50'
                ];
            @endphp

            @foreach($statuses as $status => $config)
            <div class="w-80 flex flex-col rounded-xl border {{ $borderColors[$config['color']] }} bg-white dark:bg-gray-900 shadow-sm">
                <!-- Column Header -->
                <div class="px-4 py-3 border-b {{ $borderColors[$config['color']] }} flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-{{ $config['color'] }}-500"></div>
                        <h3 class="font-semibold text-sm text-gray-700 dark:text-gray-300">
                            {{ $config['label'] }}
                        </h3>
                    </div>
                    <span class="inline-flex items-center justify-center min-w-[28px] h-6 px-2 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                        {{ $tasks[$status]->count() ?? 0 }}
                    </span>
                </div>
                
                <!-- Column Body (Draggable Area) -->
                <div id="status-{{ $status }}" class="kanban-column p-3 flex-1 overflow-y-auto space-y-3 min-h-[500px]">
                    @forelse($tasks[$status] ?? [] as $task)
                    <div data-id="{{ $task->id }}" 
                         class="task-card bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 p-3 cursor-move transition-all hover:shadow-md hover:border-gray-200 dark:hover:border-gray-600">
                        
                        <!-- Priority Indicator -->
                        <div class="flex items-center gap-2 mb-2">
                            @if($task->priority == 'urgent')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7 9h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V11a2 2 0 012-2z"/>
                                    </svg>
                                    Urgent
                                </span>
                            @elseif($task->priority == 'high')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    High
                                </span>
                            @elseif($task->priority == 'medium')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Medium
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Low
                                </span>
                            @endif
                        </div>
                        
                        <!-- Task Title -->
                        <h4 class="font-semibold text-sm text-gray-800 dark:text-white mb-2 line-clamp-2">
                            {{ $task->title }}
                        </h4>
                        
                        <!-- Task Meta -->
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>{{ $task->assignedTo->name ?? ($task->assignedUsers->first()->name ?? 'Unassigned') }}</span>
                            </div>
                            @if($task->due_date)
                            <div class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="{{ $task->due_date->isPast() && $task->status != 'completed' ? 'text-red-600 dark:text-red-400' : '' }}">
                                    {{ $task->due_date->format('M d') }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <svg class="w-10 h-10 text-gray-300 dark:text-gray-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-xs text-gray-400 dark:text-gray-500">No tasks</p>
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- SortableJS for Drag and Drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Sortable on each kanban column
        document.querySelectorAll('.kanban-column').forEach(column => {
            new Sortable(column, {
                group: {
                    name: 'kanban',
                    pull: true,
                    revertClone: false
                },
                animation: 200,
                ghostClass: 'opacity-50 bg-gray-100 dark:bg-gray-700',
                dragClass: 'shadow-lg',
                onEnd: function(evt) {
                    const taskId = evt.item.getAttribute('data-id');
                    const newStatus = evt.to.id.replace('status-', '');
                    
                    // Show loading state on the dragged item
                    const originalContent = evt.item.innerHTML;
                    evt.item.innerHTML = `
                        <div class="flex items-center justify-center py-4">
                            <div class="w-5 h-5 border-2 border-gray-300 border-t-blue-600 rounded-full animate-spin"></div>
                        </div>
                    `;
                    
                    // Send API request
                    fetch(`/kanban/${taskId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            // Success - restore content (already moved)
                            evt.item.innerHTML = originalContent;
                            // Update column counts
                            updateColumnCounts();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert on error - move back to original column
                        location.reload();
                    });
                }
            });
        });
        
        // Function to update column task counts
        function updateColumnCounts() {
            @foreach($statuses as $status => $config)
            const column{{ ucfirst(str_replace('-', '', $status)) }} = document.querySelector('#status-{{ $status }}');
            if (column{{ ucfirst(str_replace('-', '', $status)) }}) {
                const count = column{{ ucfirst(str_replace('-', '', $status)) }}.querySelectorAll('.task-card').length;
                const countBadge = document.querySelector('#status-{{ $status }}').closest('.w-80')?.querySelector('.min-w-\\[28px\\]');
                if (countBadge) {
                    countBadge.textContent = count;
                }
            }
            @endforeach
        }
    });
</script>

<style>
    /* Custom animation for spinner */
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    .animate-spin {
        animation: spin 0.6s linear infinite;
    }
    
    /* Drag ghost styles */
    .sortable-ghost {
        opacity: 0.4;
        background-color: #e5e7eb;
        border: 1px dashed #9ca3af;
    }
    
    .dark .sortable-ghost {
        background-color: #374151;
        border-color: #6b7280;
    }
    
    /* Custom scrollbar for columns */
    .kanban-column::-webkit-scrollbar {
        width: 4px;
    }
    
    .kanban-column::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .dark .kanban-column::-webkit-scrollbar-track {
        background: #1f2937;
    }
    
    .kanban-column::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .dark .kanban-column::-webkit-scrollbar-thumb {
        background: #4b5563;
    }
    
    /* Line clamp utility */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection