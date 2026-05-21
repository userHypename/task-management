@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl">
    <div class="mb-4">
        <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:underline">← Back to Tasks</a>
    </div>
    
    <!-- Task Header -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h1 class="text-3xl font-bold text-gray-800">{{ $task->title }}</h1>
                <div class="flex gap-2">
                    @php
                        $priorityColors = [
                            'urgent' => 'bg-red-600 text-white',
                            'high' => 'bg-orange-500 text-white',
                            'medium' => 'bg-yellow-500 text-white',
                            'low' => 'bg-green-500 text-white',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $priorityColors[$task->priority] ?? 'bg-gray-500' }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold 
                        @if($task->status == 'completed') bg-green-100 text-green-800
                        @elseif($task->status == 'in-progress') bg-blue-100 text-blue-800
                        @elseif($task->status == 'on-hold') bg-yellow-100 text-yellow-800
                        @elseif($task->status == 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                    </span>
                </div>
            </div>
            
            <!-- Progress Bar for multi-assignee tasks -->
            @if($task->assignedUsers->count() > 1)
                <div class="mb-6">
                    <div class="flex justify-between text-sm mb-1">
                        <span>Overall Progress</span>
                        <span>{{ $task->progress }}% Complete</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $task->progress }}%"></div>
                    </div>
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Description</h3>
                    <p class="text-gray-600">{{ $task->description ?: 'No description provided.' }}</p>
                </div>
                <div class="space-y-3">
                    <div><span class="font-bold">Project:</span> {{ $task->project->name ?? 'None' }}</div>
                    <div><span class="font-bold">Created By:</span> {{ $task->creator->name ?? 'N/A' }}</div>
                    <div><span class="font-bold">Due Date:</span> 
                        {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}
                        @if($task->due_date && $task->due_date->isPast() && $task->status != 'completed')
                            <span class="text-red-600 text-sm ml-2">(Overdue)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Assignees Section -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">Assigned Employees</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($task->assignedUsers as $assignee)
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-semibold">{{ $assignee->name }}</p>
                                <p class="text-sm text-gray-500">{{ $assignee->email }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($assignee->pivot->status == 'completed') bg-green-100 text-green-800
                                @elseif($assignee->pivot->status == 'in-progress') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('-', ' ', $assignee->pivot->status)) }}
                            </span>
                        </div>
                        @if($assignee->pivot->completion_notes)
                            <p class="text-sm text-gray-600 mt-2">{{ $assignee->pivot->completion_notes }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Employee Update Form -->
    @if(auth()->user()->isEmployee() && isset($userAssignment))
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">Update Your Progress</h2>
                <form action="{{ route('tasks.update', $task) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Status</label>
                        <select name="pivot_status" class="w-full border rounded-lg px-4 py-2">
                            <option value="pending" {{ $userAssignment->pivot->status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="in-progress" {{ $userAssignment->pivot->status == 'in-progress' ? 'selected' : '' }}>🔄 In Progress</option>
                            <option value="completed" {{ $userAssignment->pivot->status == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Completion Notes</label>
                        <textarea name="completion_notes" rows="3" 
                                  class="w-full border rounded-lg px-4 py-2">{{ $userAssignment->pivot->completion_notes }}</textarea>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    @endif
    
    <!-- Action Buttons for Manager/Admin -->
    @if(auth()->user()->isManager() || auth()->user()->isAdmin())
        <div class="flex gap-4 mt-6">
            <a href="{{ route('tasks.edit', $task) }}" 
               class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600">
                Edit Task
            </a>
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                    Delete Task
                </button>
            </form>
        </div>
    @endif
</div>
@endsection