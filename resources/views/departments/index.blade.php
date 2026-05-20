@extends('layouts.app')

@section('title', 'Departments')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Departments</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">View all departments in your organization</p>
    </div>

    <!-- Departments Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-gray-800">
        @if($departments->count() > 0)
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Name</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Description</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">Employees</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($departments as $department)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">{{ $department->name }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300 max-w-sm truncate">
                                {{ $department->description ?? 'No description' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $department->employee_count ?? 0 }}
                                </span>
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
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m5.581 0a2 2 0 100-4 2 2 0 000 4zM9 7h.01M9 11h.01M9 15h.01"
                    ></path>
                </svg>
                <p class="text-gray-600 dark:text-gray-400">No departments yet.</p>
            </div>
        @endif
    </div>
@endsection