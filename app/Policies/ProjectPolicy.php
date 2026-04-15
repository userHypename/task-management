<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Admin has full access.
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        // managers and employees can view projects list
        return true;
    }

    public function view(User $user, Project $project)
    {
        // admins handled in before; managers can view their projects; employees can view if assigned tasks in project
        if ($user->isManager()) {
            return $project->manager_id === $user->id;
        }

        // employees may view if they have tasks in the project
        return $project->tasks()->where('assigned_to', $user->id)->exists();
    }

    public function create(User $user)
    {
        // managers can create projects (admins already allowed)
        return $user->isManager();
    }

    public function update(User $user, Project $project)
    {
        // managers may update only their own projects
        return $user->isManager() && $project->manager_id === $user->id;
    }

    public function delete(User $user, Project $project)
    {
        // managers may delete their own projects
        return $user->isManager() && $project->manager_id === $user->id;
    }
}
