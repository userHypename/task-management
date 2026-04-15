@extends('layouts.app')

@section('title', 'Productivity Report')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="page-title">Productivity Report</h1>
            <p class="text-gray-600 mt-2">Top contributors by completed tasks</p>
        </div>

        <div class="flex items-center gap-3">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="btn btn-ghost">Filters</button>
                </x-slot>
                <a class="block px-4 py-2 text-sm text-gray-700">Last 7 days</a>
                <a class="block px-4 py-2 text-sm text-gray-700">Last 30 days</a>
                <a class="block px-4 py-2 text-sm text-gray-700">All time</a>
            </x-dropdown>

            <x-button variant="primary" class="inline-flex items-center">Export</x-button>
        </div>
    </div>

    <div class="card">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">User</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Email</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Completed Tasks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:table-row">
                            <td class="px-6 py-4 font-medium text-gray-900 flex items-center gap-3">
                                <x-avatar :name="$user->name" size="10" />
                                <div>
                                    <div>{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">Member</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->completed_count ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
