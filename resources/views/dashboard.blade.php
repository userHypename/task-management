<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="mb-4">Task Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-100 p-4 rounded">
                            <h4 class="text-2xl font-bold">{{ $stats['total_tasks'] }}</h4>
                            <p>Total Tasks</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded">
                            <h4 class="text-2xl font-bold">{{ $stats['completed_tasks'] }}</h4>
                            <p>Completed</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded">
                            <h4 class="text-2xl font-bold">{{ $stats['pending_tasks'] }}</h4>
                            <p>Pending</p>
                        </div>
                        <div class="bg-red-100 p-4 rounded">
                            <h4 class="text-2xl font-bold">{{ $stats['overdue_tasks'] }}</h4>
                            <p>Overdue</p>
                        </div>
                    </div>
                    @if(isset($stats['department_tasks']))
                    <div class="mt-4">
                        <h4>Department Tasks: {{ $stats['department_tasks'] }}</h4>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
