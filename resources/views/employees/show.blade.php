@extends('layouts.app')

@section('title', $employee->full_name)

@section('content')
    <!-- Page Header with Back Link -->
    <div class="mb-8">
        <a href="{{ route('employees.index') }}" class="text-blue-600 hover:text-blue-700 font-medium mb-4 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Employees
        </a>
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mt-4">{{ $employee->full_name }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $employee->position ?? 'Position not specified' }}</p>
    </div>

    <!-- Employee Details Card -->
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-8">
            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                <!-- First Name -->
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">First Name</p>
                    <p class="text-lg text-gray-900 dark:text-white">{{ $employee->first_name }}</p>
                </div>

                <!-- Last Name -->
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Last Name</p>
                    <p class="text-lg text-gray-900 dark:text-white">{{ $employee->last_name }}</p>
                </div>

                <!-- Position -->
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Position</p>
                    <p class="text-lg text-gray-900 dark:text-white">{{ $employee->position ?? 'Not specified' }}</p>
                </div>

                <!-- Department -->
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Department</p>
                    @if($employee->department)
                        <p class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                            {{ $employee->department->name }}
                        </p>
                    @else
                        <p class="text-lg text-gray-600 dark:text-gray-400">Unassigned</p>
                    @endif
                </div>

                <!-- Hire Date -->
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Hire Date</p>
                    <p class="text-lg text-gray-900 dark:text-white">
                        {{ $employee->hire_date ? $employee->hire_date->format('F d, Y') : 'Not specified' }}
                    </p>
                </div>

                <!-- User Email -->
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Email</p>
                    <p class="text-lg text-blue-600 dark:text-blue-400">{{ $employee->user->email }}</p>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">User Role</p>
                <p class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    {{ ucfirst($employee->user->role) }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <a
                    href="{{ route('employees.edit', $employee) }}"
                    class="inline-flex items-center px-6 py-3 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Employee
                </a>

                <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="inline">
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
                        Delete Employee
                    </button>
                </form>

                <a
                    href="{{ route('employees.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-900 font-medium rounded-lg hover:bg-gray-300 transition-colors dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                >
                    Back
                </a>
            </div>
        </div>
    </div>
@endsection