<?php

namespace App\Policies\V2\Project\Integrations;

use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CommonPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Общее разрешение для всех действий, чтобы не повторять один и тот же код.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function common(User $user, Project $project)
    {
        $permissions = $user->getPermissionsForProject($project);

        if(is_null($project))
            return Response::deny(message: 'Вы не назначены на данный проект');

        return $permissions->isOwner() || $permissions->isManager()
            ? Response::allow()
            : Response::deny(message: 'У вас нет полномочий на это действие');
    } //common

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user, Project $project)
    {
        return $this->common(user: $user, project: $project);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Project $project)
    {
        return $this->common(user: $user, project: $project);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Project $project)
    {
        return $this->common(user: $user, project: $project);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Project $project)
    {
        return $this->common(user: $user, project: $project);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Project $project)
    {
        return $this->common(user: $user, project: $project);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Project $project)
    {
        return $this->common(user: $user, project: $project);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Project $project)
    {
        return $this->common(user: $user, project: $project);
    }
}
