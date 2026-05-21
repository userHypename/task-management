<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', fn() => redirect('/dashboard'));


// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes (auth required)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Add this line inside the auth middleware group (after dashboard route)
Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('my-tasks');
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tasks
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create')->middleware('role:admin,manager');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store')->middleware('role:admin,manager');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit')->middleware('role:admin,manager');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy')->middleware('role:admin,manager');

    // Kanban
    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban.index');
    Route::patch('/kanban/{task}/status', [KanbanController::class, 'updateStatus'])->name('kanban.updateStatus');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/dropdown', [NotificationController::class, 'dropdown'])->name('notifications.dropdown');

    // Admin/Manager Routes
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    });

    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
        
        // User management (admin only)
        Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/admin/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
        Route::post('/admin/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include auth routes for password reset, etc
require __DIR__.'/auth.php';

