@extends('layouts.app')

@section('title', 'Create Department')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Create Department</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Add a new department to your organization</p>
    </div>

    <!-- Form Card -->
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-8">
            <form method="POST" action="{{ route('departments.store') }}">
                @csrf

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Department Name <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter department name"
                        value="{{ old('name') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <label for="description" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        placeholder="Enter department description (optional)"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Create Department
                    </button>
                    <a
                        href="{{ route('departments.index') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-900 font-medium rounded-lg hover:bg-gray-300 transition-colors dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection