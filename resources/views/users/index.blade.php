@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white">User Management</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Manage all users in the system</p>
            </div>
            <a href="{{ route('users.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New User
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap gap-4">
            <input type="text" name="search" placeholder="Search by name or email..." 
                   value="{{ request('search') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
            
            <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
            </select>
            
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            
            <button type="submit" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Filter</button>
            <a href="{{ route('users.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Reset</a>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-gray-800">
        @if($users->count() > 0)
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
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm mr-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->role === 'admin')
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Admin</span>
                                @elseif($user->role === 'manager')
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Manager</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Employee</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $user->department->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @if($user->account_status === 'active')
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('users.show', $user) }}" 
                                       class="text-blue-600 hover:text-blue-800" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" 
                                       class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $users->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-600 dark:text-gray-400">No users found.</p>
            </div>
        @endif
    </div>
@endsection