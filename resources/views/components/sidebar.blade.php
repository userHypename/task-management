<!-- Sidebar -->
<aside
    id="sidebar"
    class="hidden md:flex w-64 bg-gray-900 text-white flex-col transition-all duration-300"
>
    <!-- Sidebar content -->
    <div class="flex-1 overflow-y-auto pt-4">
        <!-- Navigation Links -->
        <nav class="space-y-2 px-4">
            <!-- Dashboard -->
            <a
                href="{{ route('dashboard') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors {{ Route::is('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}"
            >
                <svg
                    class="w-5 h-5 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-2m-9-2l4 2m0-5L9 7m5 0l4 2"
                    ></path>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Tasks -->
            <a
                href="{{ route('tasks.index') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-colors {{ Route::is('tasks.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}"
            >
                <svg
                    class="w-5 h-5 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                    ></path>
                </svg>
                <span>Tasks</span>
            </a>

            <!-- Employees (Admin/Manager only) -->
            @if (Auth::user()->isAdmin() || Auth::user()->isManager())
                <a
                    href="{{ route('employees.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors {{ Route::is('employees.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}"
                >
                    <svg
                        class="w-5 h-5 mr-3"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                    <span>Employees</span>
                </a>
            @endif

            <!-- Departments (Admin/Manager only) -->
            @if (Auth::user()->isAdmin() || Auth::user()->isManager())
                <a
                    href="{{ route('departments.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors {{ Route::is('departments.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}"
                >
                    <svg
                        class="w-5 h-5 mr-3"
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
                    <span>Departments</span>
                </a>
            @endif
        </nav>

        <!-- Section Divider -->
        <div class="my-6 mx-4 border-t border-gray-700"></div>

        <!-- Additional Navigation (optional) -->
    </div>

    <!-- Footer section -->
    <div class="px-4 py-4 border-t border-gray-700">
        <p class="text-xs text-gray-500">v1.0 • Task Manager</p>
    </div>
</aside>

<!-- Mobile Sidebar Overlay (hidden by default, shown when toggled) -->
<div
    id="sidebarOverlay"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
></div>

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

            // Close sidebar when overlay is clicked
            overlay.addEventListener('click', function () {
                sidebar.classList.add('hidden');
                overlay.classList.add('hidden');
            });

            // Close sidebar when a nav link is clicked (mobile)
            const navLinks = sidebar.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', function () {
                    if (window.innerWidth < 768) {
                        sidebar.classList.add('hidden');
                        overlay.classList.add('hidden');
                    }
                });
            });
        }
    });
</script>
