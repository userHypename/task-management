<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Task Manager') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <style>
            body {
                background-color: #f8f9fa;
            }
            .navbar-brand {
                font-weight: bold;
                font-size: 1.5rem;
            }
            .dashboard-card {
                border: none;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .dashboard-card:hover {
                box-shadow: 0 4px 8px rgba(0,0,0,0.15);
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top shadow-sm" role="navigation" aria-label="Main navigation">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    📋 {{ config('app.name', 'Task Manager') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" aria-current="{{ request()->routeIs('dashboard') ? 'page' : '' }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}" aria-current="{{ request()->routeIs('tasks.*') ? 'page' : '' }}">Tasks</a>
                        </li>
                        @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}" aria-current="{{ request()->routeIs('employees.*') ? 'page' : '' }}">Employees</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index') }}" aria-current="{{ request()->routeIs('departments.*') ? 'page' : '' }}">Departments</a>
                        </li>
                        @endif
                        
                        <!-- Search (small) -->
                        <li class="nav-item d-none d-md-flex align-items-center me-2">
                            <form class="d-flex" role="search" method="GET" action="{{ route('tasks.index') }}">
                                <input class="form-control form-control-sm" type="search" name="q" placeholder="Search tasks" aria-label="Search tasks">
                            </form>
                        </li>

                        <!-- Notifications -->
                        <li class="nav-item d-flex align-items-center me-2">
                            @php $unread = auth()->user()->unreadNotifications()->count() ?? 0; @endphp
                            <a class="nav-link position-relative" href="{{ route('notifications.index') }}" aria-label="Notifications">
                                🔔
                                @if($unread > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unread }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Header -->
        @if (isset($header))
            <header class="bg-white shadow border-bottom">
                <div class="container-fluid py-4">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Main Content -->
        <main class="container-fluid py-4">
            {{ $slot }}
        </main>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>