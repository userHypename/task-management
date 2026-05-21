<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Admins can view anyone
        if ($user->isAdmin()) {
            return true;
        }

        // Managers can view their team members
        if ($user->isManager() && $model->manager_id === $user->id) {
            return true;
        }

        // Users can view themselves
        return $user->id === $model->id;
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Only admins can update users
        // (prevent managers/employees from modifying user data)
        return $user->isAdmin();
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Only admins can delete users
        // Prevent self-deletion in controller, but this is the policy
        return $user->isAdmin();
    }

    /**
     * Determine if the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}
