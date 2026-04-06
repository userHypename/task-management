@extends('layouts.app')

@section('title', $department->name)

@section('content')
    <!-- Page Header with Back Link -->
    <div class="mb-8">
        <a href="{{ route('departments.index') }}" class="text-blue-600 hover:text-blue-700 font-medium mb-4 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Departments
        </a>
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mt-4">{{ $department->name }}</h1>
    </div>

    <!-- Department Details Card -->
    <div class="max-w-4xl mb-8">
        <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-8">
            <!-- Description -->
            <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Description</h3>
                <p class="text-gray-700 dark:text-gray-300">
                    {{ $department->description ?? 'No description provided' }}
                </p>
            </div>

            <!-- Employee Count -->
            <div class="mb-8">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Total Employees</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $department->employee_count }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a
                    href="{{ route('departments.edit', $department) }}"
                    class="inline-flex items-center px-6 py-3 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Department
                </a>

                <form method="POST" action="{{ route('departments.destroy', $department) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        onclick="return confirm('Are you sure? This action cannot be undone.')"
                        class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Department
                    </button>
                </form>

                <a
                    href="{{ route('departments.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-900 font-medium rounded-lg hover:bg-gray-300 transition-colors dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                >
                    Back
                </a>
            </div>
        </div>
    </div>

    <!-- Employees List -->
    @if($department->employees->count() > 0)
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Employees</h2>
            <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-gray-800">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Name</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Position</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Email</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Hire Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($department->employees as $employee)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">
                                    <a href="{{ route('employees.show', $employee) }}" class="text-blue-600 hover:text-blue-700">
                                        {{ $employee->full_name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $employee->position ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $employee->user->email }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-12 text-center">
            <p class="text-gray-600 dark:text-gray-400">No employees in this department yet</p>
        </div>
    @endif
@endsection