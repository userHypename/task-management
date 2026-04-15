<!-- Primary Sidebar (Todoist-like light style) -->
<aside id="sidebar" class="hidden md:flex w-64 bg-white border-r border-gray-100 flex-col transition-all duration-300">
    <!-- Branding + Add Task -->
    <div class="p-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-gray-900">TaskManager</a>
            <button id="addTaskSmall" class="text-red-600 hover:text-red-700" title="Add task">+
            </button>
        </div>

        {{-- Add task is available only to managers and admins --}}
        @if(auth()->user()->isManager() || auth()->user()->isAdmin())
            <button
                class="mt-4 w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-3 rounded-lg flex items-center justify-center gap-2"
                onclick="window.location='{{ route('tasks.create') }}'"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add task</span>
            </button>
        @endif
    </div>

    <div class="flex-1 overflow-y-auto pt-4 px-2">
        <!-- Primary Navigation (role-based) -->
        <div class="mb-4">
            <nav class="mt-2 space-y-1 px-1">
                {{-- Dashboard always visible --}}
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                    Dashboard
                </a>

                {{-- Admin-only items --}}
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('employees.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Users Management</a>
                    <a href="{{ route('departments.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Departments</a>
                    <a href="{{ route('employees.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Employees</a>
                    <a href="{{ route('projects.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Projects</a>
                    <a href="{{ route('tasks.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Tasks</a>
                    <a href="{{ route('reports.productivity') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Reports</a>
                    @if(Route::has('activity.logs'))
                        <a href="{{ route('activity.logs') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Activity Logs</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Profile</a>
                @endif

                {{-- Manager items --}}
                @if(auth()->user()->isManager())
                    <a href="{{ route('projects.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Projects</a>
                    <a href="{{ route('tasks.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Tasks</a>
                    <a href="{{ route('employees.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Team Members</a>
                    <a href="{{ route('reports.productivity') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Reports</a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Profile</a>
                @endif

                {{-- Employee items --}}
                @if(auth()->user()->isEmployee())
                    <a href="{{ route('tasks.index', ['assigned_to' => Auth::id()]) }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">My Tasks</a>
                    <a href="{{ route('projects.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">My Projects</a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">Profile</a>
                @endif
            </nav>
        </div>
        <!-- My Filters / Quick Links -->
        <div class="mb-4">
            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase">My Filters</h3>
            <nav class="mt-2 space-y-1">
                @if(auth()->user()->isEmployee())
                    <a href="{{ route('tasks.index', ['assigned_to' => Auth::id()]) }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Assigned to me
                    </a>
                @else
                    <a href="{{ route('tasks.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Tasks
                    </a>
                @endif

                <a href="{{ route('tasks.index', ['priority' => 1]) }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Priority 1
                </a>
            </nav>
        </div>

        <!-- Labels / Empty state -->
        <div class="mb-6">
            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase">Labels</h3>
            <div class="mt-3 px-3 text-sm text-gray-400">Your list of labels will show up here.</div>
        </div>

        <!-- Projects -->
        <div>
            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase">My Projects</h3>
            @php
                $user = Auth::user();
                if ($user && $user->isAdmin()) {
                    $projects = \App\Models\Project::latest()->take(10)->get();
                } elseif ($user && $user->isManager()) {
                    $projects = \App\Models\Project::where('manager_id', $user->id)->latest()->take(10)->get();
                } else {
                    $projects = \App\Models\Project::whereHas('tasks', function($q) use ($user) {
                        $q->where('assigned_to', $user?->id);
                    })->latest()->take(10)->get();
                }
            @endphp

            <nav class="mt-2 space-y-1 px-1">
                @forelse($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="flex items-center justify-between px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                        <div class="flex items-center">
                            <span class="mr-3 text-gray-400">#</span>
                            <span class="truncate">{{ $project->name }}</span>
                        </div>
                        <span class="text-xs text-gray-400">{{ $project->tasks()->count() ?? '' }}</span>
                    </a>
                @empty
                    <div class="px-3 py-2 text-sm text-gray-400">No projects to show</div>
                @endforelse
            </nav>
        </div>
    </div>

    <div class="px-4 py-4 border-t border-gray-100">
        <p class="text-xs text-gray-500">v1.0 • Task Manager</p>
    </div>
</aside>

<!-- Mobile Sidebar Overlay (hidden by default, shown when toggled) -->
<div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('hidden');
                overlay.classList.toggle('hidden');
            });

            overlay.addEventListener('click', function () {
                sidebar.classList.add('hidden');
                overlay.classList.add('hidden');
            });
        }
    });
</script>
