@extends('layouts.app')

@section('title', 'Departments')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="page-title">Departments</h1>
            <p class="body-text mt-2">Manage all departments in your organization</p>
        </div>
        <a href="{{ route('departments.create') }}" class="btn btn-primary">Add Department</a>
    </div>

    <!-- Departments Table -->
    <div class="card">
        @if($departments->count() > 0)
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Name</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Description</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Employees</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($departments as $department)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">{{ $department->name }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300 max-w-sm truncate">
                                {{ $department->description ?? 'No description' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $department->employee_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a
                                        href="{{ route('departments.show', $department) }}"
                                        class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium dark:bg-blue-900 dark:text-blue-200"
                                    >
                                        View
                                    </a>
                                    <a
                                        href="{{ route('departments.edit', $department) }}"
                                        class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-sm font-medium dark:bg-amber-900 dark:text-amber-200"
                                    >
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('departments.destroy', $department) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            onclick="return confirm('Are you sure? This action cannot be undone.')"
                                            class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium dark:bg-red-900 dark:text-red-200"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-12 text-center">
                <svg
                    class="w-16 h-16 text-gray-400 mx-auto mb-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m5.581 0a2 2 0 100-4 2 2 0 000 4zM9 7h.01M9 11h.01M9 15h.01"
                    ></path>
                </svg>
                <p class="text-gray-600 dark:text-gray-400 mb-4">No departments yet. Create one to get started!</p>
                <a
                    href="{{ route('departments.create') }}"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                >
                    Create your first department
                </a>
            </div>
        @endif
    </div>
@endsection