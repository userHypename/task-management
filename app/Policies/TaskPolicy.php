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
        return $user->id === $task->user_id || $user->isAdmin();
    }

    /**
     * Update a task - owner, manager, or admin
     */
    public function update(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->isManager() || $user->isAdmin();
    }

    /**
     * Delete a task - owner or admin
     */
    public function delete(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->isAdmin();
    }
}