<?php
namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * View a task - owner or admin
     */
    public function view(User $user, Task $task)
    {
        // Owner, assignee or admin may view
        return $user->id === $task->user_id
            || $user->id === $task->assigned_to
            || $user->isAdmin();
    }

    /**
     * Update a task - owner, manager, or admin
     */
    public function update(User $user, Task $task)
    {
        // Owner, assignee, manager or admin may update (controller enforces field-level restrictions)
        return $user->id === $task->user_id
            || $user->id === $task->assigned_to
            || $user->isManager()
            || $user->isAdmin();
    }

    /**
     * Delete a task - owner or admin
     */
    public function delete(User $user, Task $task)
    {
        // Owner or admin may delete; allow manager if they created the task
        return $user->id === $task->user_id
            || $user->isAdmin()
            || ($user->isManager() && $task->created_by === $user->id);
    }
}