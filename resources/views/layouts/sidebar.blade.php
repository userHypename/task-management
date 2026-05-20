<aside class="w-64 bg-gray-800 text-white flex-shrink-0 flex flex-col">
    <div class="p-4 text-xl font-bold border-b border-gray-700">ProTask Manager</div>
    <nav class="flex-1 p-4">
        <ul class="space-y-2">
            <li><a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Dashboard</a></li>
            <li><a href="{{ route('tasks.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Tasks</a></li>
            <li><a href="{{ route('kanban.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Kanban</a></li>
            <li><a href="{{ route('projects.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Projects</a></li>
            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <li><a href="{{ route('reports.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Reports</a></li>
                <li><a href="{{ route('employees.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Employees</a></li>
            @endif
            @if(auth()->user()->isAdmin())
                <li><a href="{{ route('departments.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Departments</a></li>
            @endif
        </ul>
    </nav>
</aside>
