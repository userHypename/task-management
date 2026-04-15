@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Create Project</h1>

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border rounded" />
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" class="w-full px-3 py-2 border rounded">{{ old('description') }}</textarea>
            @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Manager</label>
            <select name="manager_id" class="w-full px-3 py-2 border rounded">
                <option value="">--</option>
                @foreach($managers as $m)
                    <option value="{{ $m->id }}" {{ old('manager_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                @endforeach
            </select>
            @error('manager_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border rounded">
                <option value="planned" {{ old('status') == 'planned' ? 'selected' : '' }}>Planned</option>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
    </form>
</div>
@endsection
