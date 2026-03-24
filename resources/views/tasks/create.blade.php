@extends('layouts.app')

@section('content')

<h2>Create Task</h2>

<form method="POST" action="{{ route('tasks.store') }}">
    @csrf

    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label>Due Date</label>
        <input type="date" name="due_date" class="form-control">
    </div>

    <div class="mb-3">
        <label>Priority</label>
        <select name="priority" class="form-control">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>
    </div>

    <button class="btn btn-success">Save Task</button>
</form>

@endsection
