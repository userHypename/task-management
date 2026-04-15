<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', 'Task Manager')</title>

    <!-- Fonts: single family for consistency -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-neutral text-text font-sans">
    <div class="flex h-screen bg-neutral">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main content wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            @include('components.navbar')

            <!-- Main content area -->
            <main class="flex-1 overflow-auto">
                <div class="p-4 md:p-8 max-w-7xl mx-auto">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div
                            id="alert-success"
                            class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative"
                            role="alert"
                        >
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg
                                    class="fill-current h-6 w-6 text-green-500 cursor-pointer"
                                    role="button"
                                    onclick="document.getElementById('alert-success').remove()"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <title>Close</title>
                                    <path
                                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.696l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"
                                    />
                                </svg>
                            </span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            id="alert-error"
                            class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative"
                            role="alert"
                        >
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg
                                    class="fill-current h-6 w-6 text-red-500 cursor-pointer"
                                    role="button"
                                    onclick="document.getElementById('alert-error').remove()"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <title>Close</title>
                                    <path
                                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.696l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"
                                    />
                                </svg>
                            </span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div
                            id="alert-validation"
                            class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded"
                            role="alert"
                        >
                            <strong class="font-bold">Validation Error!</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Page Content -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>

    <!-- Auto-dismiss alerts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('[id^="alert-"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
