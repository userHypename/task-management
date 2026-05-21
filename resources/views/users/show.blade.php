@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-700 font-medium mb-4 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Users
        </a>
        <div class="flex justify-between items-start mt-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $user->email }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('users.edit', $user) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700">
                    Edit User
                </a>
                <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Delete this user?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- User Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Profile Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</p>
                        <p class="text-lg">
                            @if($user->role === 'admin')
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Administrator</span>
                            @elseif($user->role === 'manager')
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">Manager</span>
                            @else
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Employee</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Status</p>
                        <p class="text-lg">
                            @if($user->account_status === 'active')
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Position</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $user->position ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $user->phone ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $user->department->name ?? 'Not assigned' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Manager</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $user->manager->name ?? 'No manager assigned' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bio</p>
                        <p class="text-gray-900 dark:text-white">{{ $user->bio ?? 'No bio provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Tasks -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Tasks</h2>
                @if($user->createdTasks->count() > 0)
                    <div class="space-y-3">
                        @foreach($user->createdTasks->take(5) as $task)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                                <a href="{{ route('tasks.show', $task) }}" class="font-semibold text-gray-900 dark:text-white hover:text-blue-600">
                                    {{ $task->title }}
                                </a>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($task->description, 100) }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No tasks created by this user.</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Reset Password Card -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Reset Password</h2>
                <form method="POST" action="{{ route('users.resetPassword', $user) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                        <input type="password" name="password" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                        Reset Password
                    </button>
                </form>
            </div>

            <!-- Toggle Status Card -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Account Status</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Current status: <strong>{{ ucfirst($user->account_status) }}</strong>
                </p>
                <form method="POST" action="{{ route('users.toggleStatus', $user) }}">
                    @csrf
                    @if($user->account_status === 'active')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">
                            Deactivate Account
                        </button>
                    @else
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700">
                            Activate Account
                        </button>
                    @endif
                </form>
            </div>

            <!-- Meta Info -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Meta Information</h2>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium text-gray-500">Joined:</span> {{ $user->created_at ? $user->created_at->format('F d, Y') : 'N/A' }}</p>
                    <p><span class="font-medium text-gray-500">Last Updated:</span> {{ $user->updated_at ? $user->updated_at->format('F d, Y') : 'N/A' }}</p>
                    <p><span class="font-medium text-gray-500">User ID:</span> {{ $user->id }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection