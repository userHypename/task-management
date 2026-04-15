@extends('layouts.app')

@section('title', 'Employees')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="page-title">Employees</h1>
            <p class="body-text mt-2">Manage all employees and their details</p>
        </div>
        <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
    </div>

    <!-- Employees Table -->
    <div class="card">
        @if($employees->count() > 0)
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Name</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Position</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Department</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Email</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($employees as $emp)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $emp->position }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                    {{ $emp->department->name ?? 'Unassigned' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $emp->user->email }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a
                                        href="{{ route('employees.show', $emp) }}"
                                        class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium dark:bg-blue-900 dark:text-blue-200"
                                    >
                                        View
                                    </a>
                                    <a
                                        href="{{ route('employees.edit', $emp) }}"
                                        class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-sm font-medium dark:bg-amber-900 dark:text-amber-200"
                                    >
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('employees.destroy', $emp) }}" class="inline">
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
                        d="M17 20h5v-2a3 3 0 00-5.856-1.487M7 20H2v-2a3 3 0 015.856-1.487M12 14a4 4 0 100-8 4 4 0 000 8zm0 0a4 4 0 015.856 1.487M12 14a4 4 0 00-5.856 1.487"
                    ></path>
                </svg>
                <p class="text-gray-600 dark:text-gray-400 mb-4">No employees yet. Add one to get started!</p>
                <a
                    href="{{ route('employees.create') }}"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                >
                    Add your first employee
                </a>
            </div>
        @endif
    </div>
@endsection
