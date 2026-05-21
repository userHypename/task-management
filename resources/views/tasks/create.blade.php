@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:underline">← Back to Tasks</a>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Create New Task</h1>
            
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('tasks.store') }}" method="POST" id="taskForm">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Task Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" 
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Description</label>
                    <textarea name="description" rows="5" 
                              class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Project <span class="text-red-500">*</span></label>
                        <select name="project_id" class="w-full border rounded-lg px-4 py-2" required>
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Priority <span class="text-red-500">*</span></label>
                        <select name="priority" class="w-full border rounded-lg px-4 py-2" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🔴 High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>🔥 Urgent</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full border rounded-lg px-4 py-2" required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="in-progress" {{ old('status') == 'in-progress' ? 'selected' : '' }}>🔄 In Progress</option>
                            <option value="on-hold" {{ old('status') == 'on-hold' ? 'selected' : '' }}>⏸ On Hold</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" 
                               class="w-full border rounded-lg px-4 py-2">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Assign to Employees <span class="text-red-500">*</span></label>
                    <select name="assigned_users[]" multiple required 
                            class="w-full border rounded-lg px-4 py-2 h-32 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" 
                                {{ in_array($employee->id, old('assigned_users', [])) ? 'selected' : '' }}>
                                {{ $employee->name }} ({{ $employee->position ?? 'Employee' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple employees</p>
                </div>
                
                <div class="flex justify-end gap-4 pt-4 border-t">
                    <a href="{{ route('tasks.index') }}" class="px-6 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('taskForm').addEventListener('submit', function(e) {
        var select = document.querySelector('select[name="assigned_users[]"]');
        if (select.selectedOptions.length === 0) {
            e.preventDefault();
            alert('Please select at least one employee to assign this task to.');
        }
    });
</script>
@endsection