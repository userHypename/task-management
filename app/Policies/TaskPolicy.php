<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine if the user can view any tasks.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can list tasks (filtered by role)
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        // Admin can view any task
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can view tasks they created
        if ($user->isManager() && $task->created_by === $user->id) {
            return true;
        }

        // Employee can view tasks assigned to them (many-to-many)
        if ($user->isEmployee() && $task->assignedUsers()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isManager() || $user->isAdmin();
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        // Admin can update any task
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can update tasks they created
        if ($user->isManager() && $task->created_by === $user->id) {
            return true;
        }

        // Employee can update status if task is assigned to them
        if ($user->isEmployee() && $task->assignedUsers()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        // Admin can delete any task
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can delete tasks they created (if not completed)
        if ($user->isManager() && $task->created_by === $user->id && !$task->is_completed) {
            return true;
        }

        // Employees cannot delete tasks
        return false;
    }

    /**
     * Determine if the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return $user->isAdmin() || ($user->isManager() && $task->created_by === $user->id);
    }

    /**
     * Determine if the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->isAdmin();
    }
}