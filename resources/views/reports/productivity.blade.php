@extends('layouts.app')

@section('title', 'Productivity Report')

@section('content')
    <div class="mb-6">
        <h1 class="page-title">Productivity Report</h1>
        <p class="text-gray-600 mt-2">Top contributors by completed tasks</p>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">User</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Email</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Completed Tasks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
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
