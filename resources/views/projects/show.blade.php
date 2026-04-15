@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">{{ $project->name }}</h1>

    <p>{{ $project->description }}</p>

    <p>Status: {{ $project->status }}</p>
    <p>Manager: {{ optional($project->manager)->name }}</p>

    <a href="{{ route('projects.index') }}">Back</a>
</div>
@endsection
