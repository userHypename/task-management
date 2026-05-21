@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-700 font-medium mb-4 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Users
        </a>
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mt-4">Edit User</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Update user information for {{ $user->name }}</p>
    </div>

    <!-- Form -->
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-8">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Full Name <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Email Address <span class="text-red-600">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Role <span class="text-red-600">*</span>
                    </label>
                    <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                    @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Account Status <span class="text-red-600">*</span>
                    </label>
                    <select name="account_status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                        <option value="active" {{ old('account_status', $user->account_status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('account_status', $user->account_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('account_status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Department
                    </label>
                    <select name="department_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Manager
                    </label>
                    <select name="manager_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                        <option value="">Select Manager</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id', $user->manager_id) == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }} ({{ ucfirst($manager->role) }})
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Position
                    </label>
                    <input type="text" name="position" value="{{ old('position', $user->position) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                    @error('position') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Phone
                    </label>
                    <input type="text" name="phone" value="{{ old('phone',