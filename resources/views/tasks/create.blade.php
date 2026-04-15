@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create Task</h1>
        <p class="text-gray-600 mt-2">Create a new task and assign it to an employee</p>
    </div>

    <!-- Form -->
    <div class="max-w-2xl">
        <div class="bg-white border border-gray-200 rounded-md p-8">
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">
                        Title *
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="e.g., Review project proposal"
                        value="{{ old('title') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-slate-500 focus:border-transparent outline-none transition"
                    />
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        placeholder="Add task details..."
                        rows="4"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-slate-500 focus:border-transparent outline-none transition"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assign To -->
                <div class="mb-6">
                    <label for="assigned_to" class="block text-sm font-semibold text-gray-900 mb-2">
                        Assign To
                    </label>
                    <select
                        id="assigned_to"
                        name="assigned_to"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-slate-500 focus:border-transparent outline-none transition"
                    >
                        <option value="">Select an employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('assigned_to') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Project -->
                <div class="mb-6">
                    <label for="project_id" class="block text-sm font-semibold text-gray-900 mb-2">Project</label>
                    <select id="project_id" name="project_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-slate-500 focus:border-transparent outline-none transition">
                        <option value="">None</option>
                        @if(!empty($projects))
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ old('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('project_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="due_date" class="block text-sm font-semibold text-gray-900 mb-2">
                            Due Date
                        </label>
                        <input
                            type="date"
                            id="due_date"
                            name="due_date"
                            value="{{ old('due_date') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-slate-500 focus:border-transparent outline-none transition"
                        />
                        @error('due_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-semibold text-gray-900 mb-2">
                            Priority *
                        </label>
                        <select
                            id="priority"
                            name="priority"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-slate-500 focus:border-transparent outline-none transition"
                        >
                            <option value="">Select priority</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row sm:justify-start gap-3 pt-6 border-t border-gray-200">
                    <button
                        type="submit"
                        class="btn btn-primary w-full sm:w-auto"
                    >
                        Create Task
                    </button>
                    <a
                        href="{{ route('tasks.index') }}"
                        class="btn btn-ghost w-full sm:w-auto"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

