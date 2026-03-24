@extends('layouts.app')

@section('content')

<h2>Your Tasks</h2>

<a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">+ Add Task</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Title</th>
            <th>Due Date</th>
            <th>Priority</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tasks as $task)
        <tr>
            <td>{{ $task->title }}</td>
            <td>{{ $task->due_date }}</td>
            <td>{{ ucfirst($task->priority) }}</td>
            <td>
                @if($task->is_completed)
                    <span class="badge bg-success">Completed</span>
                @else
                    <span class="badge bg-warning">Pending</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
