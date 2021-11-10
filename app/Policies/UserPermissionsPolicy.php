<?php

namespace App\Policies;

use App\Models\Project\Project;
use App\Models\Project\UserPermissions;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPermissionsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\UserPermissions  $UserPermissions
     * @return mixed
     */
    public function view(User $user, UserPermissions $UserPermissions)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project $project
     * @return mixed
     */
    public function create(User $user, Project $project)
    {
        return $project->isOwner() or $user->isManagerFor($project);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        return $project->isOwner() or $user->isManagerFor($project);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\UserPermissions  $tUserPermissions
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        return $project->isOwner() or $user->isManagerFor($project);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\UserPermissions  $UserPermissions
     * @return mixed
     */
    public function restore(User $user, UserPermissions $UserPermissions)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\UserPermissions  $UserPermissions
     * @return mixed
     */
    public function forceDelete(User $user, UserPermissions $UserPermissions)
    {
        //
    }
}
