@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-2xl">
    <div class="bg-white p-8 rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Edit Task</h1>
        
        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea name="description" class="w-full border rounded px-3 py-2" rows="4" required>{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Project</label>
                    <select name="project_id" class="w-full border rounded px-3 py-2">
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Priority</label>
                    <select name="priority" class="w-full border rounded px-3 py-2">
                        <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Assign To</label>
                    <select name="assigned_to" class="w-full border rounded px-3 py-2">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Status</label>
                    <select name="status" class="w-full border rounded px-3 py-2">
                        <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in-progress" {{ $task->status == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <a href="{{ url()->previous() }}" class="bg-gray-300 px-6 py-2 rounded font-bold">Cancel</a>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded font-bold">
                    Update Task
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
                                {{ \->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class=\"mb-4\">
                    <label class=\"block text-gray-700 font-bold mb-2\">Priority</label>
                    <select name=\"priority\" class=\"w-full border rounded px-3 py-2\">
                        <option value=\"low\" {{ (old('priority', \->priority ?? '') == 'low') ? 'selected' : '' }}>Low</option>
                        <option value=\"medium\" {{ (old('priority', \->priority ?? '') == 'medium') ? 'selected' : '' }}>Medium</option>
                        <option value=\"high\" {{ (old('priority', \->priority ?? '') == 'high') ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <div class=\"mb-4\">
                    <label class=\"block text-gray-700 font-bold mb-2\">Assign To</label>
                    <select name=\"assigned_to\" class=\"w-full border rounded px-3 py-2\">
                        <option value=\"\">Unassigned</option>
                        @foreach(\ as \)
                            <option value=\"{{ \->id }}\" {{ (old('assigned_to', \->assigned_to ?? '') == \->id) ? 'selected' : '' }}>
                                {{ \->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class=\"mb-4\">
                    <label class=\"block text-gray-700 font-bold mb-2\">Due Date</label>
                    <input type=\"date\" name=\"due_date\" value=\"{{ old('due_date', isset(\) && \->due_date ? \->due_date->format('Y-m-d') : '') }}\" class=\"w-full border rounded px-3 py-2\">
                </div>

                @if(isset(\))
                <div class=\"mb-4\">
                    <label class=\"block text-gray-700 font-bold mb-2\">Status</label>
                    <select name=\"status\" class=\"w-full border rounded px-3 py-2\">
                        <option value=\"pending\" {{ \->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value=\"in-progress\" {{ \->status == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value=\"completed\" {{ \->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                @endif
            </div>

            <div class=\"flex justify-end gap-4 mt-6\">
                <a href=\"{{ url()->previous() }}\" class=\"bg-gray-300 px-6 py-2 rounded font-bold\">Cancel</a>
                <button type=\"submit\" class=\"bg-blue-500 text-white px-6 py-2 rounded font-bold\">
                    {{ isset(\) ? 'Update Task' : 'Create Task' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
