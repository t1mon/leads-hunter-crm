<?php

namespace App\Policies;

use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
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
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function view(User $user, Project $project)
    {
        if ( $project->isOwner() or ($user->isManagerFor($project) or $user->isWatcher($project)) )
            return Response::allow();
        else
            return Response::deny();
    }

    /**
     * Определяет, может ли пользователь просматривать страницы с настройками
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return bool
     */

    public function settings(User $user, Project $project)
    {
        //Настройки может просматривать только менеджер или создатель
        if ($project->isOwner() or $user->isManagerFor($project) or $user->isAdmin())
            return Response::allow();
        else
            return Response::deny();
    }

    public function export(User $user, Project $project) //Определяет, может ли пользователь выгружать лиды из проекта
    {
        return $user->isAdmin() || $user->isInProject($project)
         ? Response::allow()
         : Response::deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return bool
     */
    public function update(User $user, Project $project)
    {
        //Обновлять проект может только владелец
        return $project->isOwner() ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return bool
     */
    public function delete(User $user, Project $project)
    {
        //Удалить проект может только владелец
        return $project->isOwner() ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function restore(User $user, Project $project)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function forceDelete(User $user, Project $project)
    {
        //
    }
}
