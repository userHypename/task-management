@extends('layouts.app')

@section('title', 'Add Employee')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Add Employee</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Create a new employee record</p>
    </div>

    <!-- Form Card -->
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-8">
            <form method="POST" action="{{ route('employees.store') }}">
                @csrf

                <!-- User Account -->
                <div class="mb-6">
                    <label for="user_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        User Account <span class="text-red-600">*</span>
                    </label>
                    <select
                        id="user_id"
                        name="user_id"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Select a user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- First Name -->
                <div class="mb-6">
                    <label for="first_name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        First Name <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        placeholder="Enter first name"
                        value="{{ old('first_name') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                    @error('first_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div class="mb-6">
                    <label for="last_name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Last Name <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        placeholder="Enter last name"
                        value="{{ old('last_name') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                    @error('last_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Position -->
                <div class="mb-6">
                    <label for="position" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Position
                    </label>
                    <input
                        type="text"
                        id="position"
                        name="position"
                        placeholder="Enter job position"
                        value="{{ old('position') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                    @error('position')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div class="mb-6">
                    <label for="department_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Department
                    </label>
                    <select
                        id="department_id"
                        name="department_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Select a department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hire Date -->
                <div class="mb-8">
                    <label for="hire_date" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Hire Date
                    </label>
                    <input
                        type="date"
                        id="hire_date"
                        name="hire_date"
                        value="{{ old('hire_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                    @error('hire_date')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Save Employee
                    </button>
                    <a
                        href="{{ route('employees.index') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-900 font-medium rounded-lg hover:bg-gray-300 transition-colors dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
