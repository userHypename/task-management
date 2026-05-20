@extends('layouts.app')

@section('title', 'Employees')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Employees</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">View all employees and their details</p>
    </div>

    <!-- Employees Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-gray-800">
        @if($employees->count() > 0)
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Name</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Position</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Department</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Email</th>
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
                                {{ $emp->user->email ?? 'N/A' }}
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
                <p class="text-gray-600 dark:text-gray-400">No employees yet.</p>
            </div>
        @endif
    </div>
@endsection
