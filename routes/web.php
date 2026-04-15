<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskActivityController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/test', [TestController::class, 'test']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kanban board view for tasks
    Route::get('tasks/kanban', [TaskController::class, 'kanban'])->name('tasks.kanban');
    // AJAX move endpoint for Kanban interactions
    Route::patch('tasks/{task}/move', [TaskController::class, 'move'])->name('tasks.move');

    Route::resource('tasks', TaskController::class);
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::post('tasks/{task}/comments', [TaskCommentController::class, 'store'])->name('tasks.comments.store');
    Route::delete('task-comments/{comment}', [TaskCommentController::class, 'destroy'])->name('task-comments.destroy');
    Route::get('tasks/{task}/activities', [TaskActivityController::class, 'index'])->name('tasks.activities.index');
    // Admin-only resources
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::resource('departments', DepartmentController::class);
    });

    // Projects and reports available to Admin and Manager
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::resource('projects', ProjectController::class);

        Route::get('reports/completed', [ReportController::class, 'completed'])->name('reports.completed');
        Route::get('reports/pending', [ReportController::class, 'pending'])->name('reports.pending');
        Route::get('reports/overdue', [ReportController::class, 'overdue'])->name('reports.overdue');
        Route::get('reports/productivity', [ReportController::class, 'productivity'])->name('reports.productivity');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
