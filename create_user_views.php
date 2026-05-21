<?php
// Script to create user view files

$baseDir = __DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'users';

// Create directory if it doesn't exist
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0755, true);
    echo "Created directory: $baseDir\n";
}

$files = [
    'index.blade.php' => <<<'EOT'
@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Users</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Manage system users and their permissions</p>
            </div>
            @if(auth()->user()->isAdmin())
                <a
                    href="{{ route('users.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors w-full md:w-auto justify-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add User
                </a>
            @endif
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-6 mb-8">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    placeholder="Name or email..."
                    value="{{ request('search') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
            </div>

            <!-- Role Filter -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                <select
                    id="role"
                    name="role"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="employee" {{ request('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select
                    id="status"
                    name="status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Department Filter -->
            <div>
                <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                <select
                    id="department"
                    name="department"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button
                    type="submit"
                    class="flex-1 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                >
                    Filter
                </button>
                <a
                    href="{{ route('users.index') }}"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-900 font-medium rounded-lg hover:bg-gray-300 transition-colors dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 text-center"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-gray-800">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Name</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Email</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Role</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Department</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Status</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <!-- Name -->
                                <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">
                                    {{ $user->name }}
                                </td>

                                <!-- Email -->
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $user->email }}
                                </td>

                                <!-- Role -->
                                <td class="px-6 py-4">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    @elseif($user->role === 'manager')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Department -->
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    @if($user->department)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                            {{ $user->department->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    @if($user->account_status === 'active')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a
                                            href="{{ route('users.show', $user) }}"
                                            class="inline-flex items-center px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800"
                                            title="View"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>

                                        @if(auth()->user()->isAdmin() || auth()->user()->id === $user->id)
                                            <a
                                                href="{{ route('users.edit', $user) }}"
                                                class="inline-flex items-center px-3 py-2 text-sm bg-amber-100 text-amber-700 rounded hover:bg-amber-200 transition-colors dark:bg-amber-900 dark:text-amber-200 dark:hover:bg-amber-800"
                                                title="Edit"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        @endif

                                        @if(auth()->user()->isAdmin())
                                            @if($user->account_status === 'active')
                                                <form method="POST" action="{{ route('users.deactivate', $user) }}" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Deactivate this user?')"
                                                        class="inline-flex items-center px-3 py-2 text-sm bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition-colors dark:bg-yellow-900 dark:text-yellow-200 dark:hover:bg-yellow-800"
                                                        title="Deactivate"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('users.activate', $user) }}" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center px-3 py-2 text-sm bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors dark:bg-green-900 dark:text-green-200 dark:hover:bg-green-800"
                                                        title="Activate"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($user->id !== auth()->user()->id)
                                                <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Delete this user? This action cannot be undone.')"
                                                        class="inline-flex items-center px-3 py-2 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800"
                                                        title="Delete"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $users->links() }}
                </div>
            @endif
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
                <p class="text-gray-600 dark:text-gray-400">No users found.</p>
            </div>
        @endif
    </div>
@endsection
EOT,
    'create.blade.php' => <<<'EOT'
@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Create User</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Add a new user to the system</p>
    </div>

    <!-- Form Card -->
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-8">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Full Name <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter full name"
                        value="{{ old('name') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 @enderror"
                    />
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Email <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="user@example.com"
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('email') border-red-500 @enderror"
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Password <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('password') border-red-500 @enderror"
                    />
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Confirm Password <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirm password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                </div>

                <!-- Role -->
                <div class="mb-6">
                    <label for="role" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Role <span class="text-red-600">*</span>
                    </label>
                    <select
                        id="role"
                        name="role"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('role') border-red-500 @enderror"
                    >
                        <option value="">Select a role</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                    @error('role')
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

                <!-- Manager -->
                <div class="mb-6">
                    <label for="manager_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Manager
                    </label>
                    <select
                        id="manager_id"
                        name="manager_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">None</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
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

                <!-- Phone -->
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Phone
                    </label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        placeholder="Phone number"
                        value="{{ old('phone') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div class="mb-8">
                    <label for="bio" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Bio
                    </label>
                    <textarea
                        id="bio"
                        name="bio"
                        placeholder="User bio"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >{{ old('bio') }}</textarea>
                    @error('bio')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Create User
                    </button>
                    <a
                        href="{{ route('users.index') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-900 font-medium rounded-lg hover:bg-gray-300 transition-colors dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
EOT,
    'edit.blade.php' => <<<'EOT'
@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <!-- Page Header with Back Link -->
    <div class="mb-8">
        <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-700 font-medium mb-4 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Users
        </a>
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mt-4">Edit User</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Update user information</p>
    </div>

    <!-- Form Card -->
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-8">
            <!-- User Information Form -->
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Full Name <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter full name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 @enderror"
                    />
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Email <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="user@example.com"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('email') border-red-500 @enderror"
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="mb-6">
                    <label for="role" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Role <span class="text-red-600">*</span>
                    </label>
                    <select
                        id="role"
                        name="role"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('role') border-red-500 @enderror"
                    >
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="employee" {{ $user->role === 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                    @error('role')
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
                            <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Manager -->
                <div class="mb-6">
                    <label for="manager_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Manager
                    </label>
                    <select
                        id="manager_id"
                        name="manager_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">None</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id', $user->manager_id) == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
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
                        value="{{ old('position', $user->position) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                    @error('position')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Phone
                    </label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        placeholder="Phone number"
                        value="{{ old('phone', $user->phone) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div class="mb-6">
                    <label for="bio" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Bio
                    </label>
                    <textarea
                        id="bio"
                        name="bio"
                        placeholder="User bio"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Status -->
                <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                    <label for="account_status" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Account Status
                    </label>
                    <select
                        id="account_status"
                        name="account_status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="active" {{ $user->account_status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $user->account_status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('account_status')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Save Changes
                    </button>
                    <a
                        href="{{ route('users.index') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-900 font-medium rounded-lg hover:bg-gray-300 transition-colors dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                    >
                        Cancel
                    </a>
                </div>
            </form>

            <!-- Password Reset Section -->
            <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Change Password</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Leave empty to keep the current password</p>

                <form method="POST" action="{{ route('users.update-password', $user) }}">
                    @csrf
                    @method('PUT')

                    <!-- New Password -->
                    <div class="mb-6">
                        <label for="new_password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            New Password
                        </label>
                        <input
                            type="password"
                            id="new_password"
                            name="new_password"
                            placeholder="Enter new password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('new_password') border-red-500 @enderror"
                        />
                        @error('new_password')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-6">
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Confirm New Password
                        </label>
                        <input
                            type="password"
                            id="new_password_confirmation"
                            name="new_password_confirmation"
                            placeholder="Confirm new password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        />
                    </div>

                    <!-- Password Submit -->
                    <button
                        type="submit"
                        class="px-6 py-2 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 transition-colors"
                    >
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
EOT,
    'show.blade.php' => <<<'EOT'
@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <!-- Page Header with Back Link -->
    <div class="mb-8">
        <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-700 font-medium mb-4 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Users
        </a>
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mt-4">{{ $user->name }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $user->position ?? 'Position not specified' }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- User Details Card -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-8 mb-8">
                <!-- Basic Info -->
                <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Name -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Full Name</p>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $user->name }}</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Email</p>
                            <p class="text-lg text-blue-600 dark:text-blue-400">{{ $user->email }}</p>
                        </div>

                        <!-- Role -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Role</p>
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    {{ ucfirst($user->role) }}
                                </span>
                            @elseif($user->role === 'manager')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                    {{ ucfirst($user->role) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ ucfirst($user->role) }}
                                </span>
                            @endif
                        </div>

                        <!-- Status -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Status</p>
                            @if($user->account_status === 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <!-- Position -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Position</p>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $user->position ?? 'Not specified' }}</p>
                        </div>

                        <!-- Phone -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Phone</p>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $user->phone ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Department & Manager -->
                <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Organization</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Department -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Department</p>
                            @if($user->department)
                                <p class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                    {{ $user->department->name }}
                                </p>
                            @else
                                <p class="text-lg text-gray-600 dark:text-gray-400">Unassigned</p>
                            @endif
                        </div>

                        <!-- Manager -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Manager</p>
                            @if($user->manager)
                                <a href="{{ route('users.show', $user->manager) }}" class="text-lg text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ $user->manager->name }}
                                </a>
                            @else
                                <p class="text-lg text-gray-600 dark:text-gray-400">No manager</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Bio -->
                @if($user->bio)
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Bio</h2>
                        <p class="text-gray-600 dark:text-gray-300 whitespace-pre-wrap">{{ $user->bio }}</p>
                    </div>
                @endif
            </div>

            <!-- Managed Employees (if user is a manager) -->
            @if($user->isManager() && $user->managedEmployees->count() > 0)
                <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Team Members</h2>
                    <div class="space-y-4">
                        @foreach($user->managedEmployees as $employee)
                            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $employee->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $employee->position ?? 'No position' }}</p>
                                </div>
                                <a
                                    href="{{ route('users.show', $employee) }}"
                                    class="px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800"
                                >
                                    View
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Task Statistics -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-8 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Task Statistics</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Tasks Created</span>
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $user->createdTasks()->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Tasks Assigned</span>
                        <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $user->assignedTasks()->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Completed Tasks</span>
                        <span class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $user->assignedTasks()->where('status', 'completed')->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Unread Notifications -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-8 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Notifications</h2>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                    {{ $user->notifications()->where('read_at', null)->count() }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Unread notifications</p>
            </div>

            <!-- Action Buttons -->
            @if(auth()->user()->isAdmin() || auth()->user()->id === $user->id)
                <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-8">
                    <div class="space-y-3">
                        <a
                            href="{{ route('users.edit', $user) }}"
                            class="block w-full px-4 py-3 bg-blue-600 text-white text-center font-medium rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit User
                        </a>

                        @if(auth()->user()->isAdmin())
                            <a
                                href="{{ route('users.index') }}"
                                class="block w-full px-4 py-3 bg-gray-200 text-gray-900 text-center font-medium rounded-lg hover:bg-gray-300 transition-colors dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                            >
                                Back to Users
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
EOT,
];

// Create files
foreach ($files as $filename => $content) {
    $filepath = $baseDir . DIRECTORY_SEPARATOR . $filename;
    file_put_contents($filepath, $content);
    echo "✓ Created: $filename\n";
}

echo "\nAll view files created successfully!\n";
?>
