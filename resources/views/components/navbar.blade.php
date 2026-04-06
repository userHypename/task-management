<nav class="bg-white border-b border-gray-200 px-4 py-2.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-wrap justify-between items-center">
        <!-- Logo and Brand -->
        <div class="flex justify-start items-center">
            <button
                id="toggleSidebar"
                type="button"
                class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
            >
                <span class="sr-only">Toggle sidebar</span>
                <svg
                    class="w-6 h-6"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        clip-rule="evenodd"
                        fill-rule="evenodd"
                        d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
                    ></path>
                </svg>
            </button>
            <a href="{{ route('dashboard') }}" class="flex ml-2 md:mr-8 items-center">
                <span class="self-center text-lg font-semibold text-gray-900 dark:text-white">Task Manager</span>
            </a>
            <nav class="hidden md:flex md:ml-6 space-x-4">
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>
                <a href="{{ route('tasks.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Tasks</a>
            </nav>
        </div>

        <!-- Right section: User dropdown -->
        <div class="flex items-center">
            <div class="flex items-center ml-3">
                <button
                    type="button"
                    class="flex text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                    id="user-menu-button"
                    aria-expanded="false"
                    data-dropdown-toggle="dropdown-user"
                >
                    <span class="sr-only">Open user menu</span>
                    <div
                        class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold"
                    >
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </button>
            </div>

            <!-- Dropdown menu -->
            <div
                id="dropdown-user"
                class="hidden z-50 my-4 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600 absolute right-4 top-12"
            >
                <div class="py-3 px-4">
                    <span class="block text-sm text-gray-900 dark:text-white"
                        >{{ Auth::user()->name }}</span
                    >
                    <span class="block text-sm font-medium text-gray-500 truncate dark:text-gray-400"
                        >{{ Auth::user()->email }}</span
                    >
                    <span
                        class="block text-xs font-medium text-blue-600 mt-1 uppercase"
                    >
                        {{ Auth::user()->role }}
                    </span>
                </div>
                <ul class="py-1">
                    <li>
                        <a
                            href="{{ route('profile.edit') }}"
                            class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                        >
                            My Profile
                        </a>
                    </li>
                </ul>
                <ul class="py-1">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full text-left block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                            >
                                Sign out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Flowbite JS for dropdown -->
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
