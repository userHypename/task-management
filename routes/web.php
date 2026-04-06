<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/test', [TestController::class, 'test']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('tasks', TaskController::class);

    // Employee routes (only admins/managers can access)
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::resource('departments', DepartmentController::class);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
