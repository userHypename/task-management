<aside class="w-64 bg-gray-800 text-white flex-shrink-0 flex flex-col">
    <div class="p-4 text-xl font-bold border-b border-gray-700">ProTask Manager</div>
    <nav class="flex-1 p-4">
        <ul class="space-y-2">
            <li><a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Dashboard</a></li>
            
            <!-- My Tasks - For Employees -->
            @if(auth()->user()->role === 'employee')
                <li><a href="{{ route('my-tasks') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">📋 My Tasks</a></li>
            @endif
            
            <li><a href="{{ route('tasks.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">All Tasks</a></li>
            <li><a href="{{ route('kanban.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Kanban Board</a></li>

            
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                <li><a href="{{ route('reports.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Reports</a></li>
                <li><a href="{{ route('employees.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Employees</a></li>
            @endif
            
            @if(auth()->user()->role === 'admin')
                <li><a href="{{ route('departments.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Departments</a></li>
                <li><a href="{{ route('users.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Users</a></li>
            @endif
        </ul>
    </nav>
</aside>