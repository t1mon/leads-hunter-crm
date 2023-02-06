<?php

namespace App\Policies\V2\Project;

use App\Models\Project\Blacklist;
use App\Models\Project\Project;
use App\Models\User;
use App\Repositories\Project\UserPermissions\ReadRepository as PermissionsRepisitory;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BlacklistPolicy
{
    use HandlesAuthorization;

    public function __construct(
        private PermissionsRepisitory $permissionsRepository,
    )
    {
        //      
    }

    public function general(User $user, Project $project) //Общая функция, чтобы избежать повторения кода
    {
        //Если пользователь админ, то ему сразу даётся разрешение
        if($user->isAdmin())
            return Response::allow();

        //Если пользователь не назначен в проект, ему отказывается в доступе
        $permissions = $this->permissionsRepository->findByUserInProject(user: $user, project: $project);
        if(is_null($permissions))
            return Response::deny();

        //Если пользователь является владельцем, менеджером или младшим менеджером, ему даётся доступ
        if($permissions->isOwner() || $permissions->isManager() ) //TODO Добавить isJuniorManager после мержа с back_managers
            return Response::allow();
        else
            return Response::deny();
    } //general

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user, Project $project)
    {
        return $this->general(user: $user, project: $project);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Blacklist  $blacklist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Blacklist $blacklist)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Project $project)
    {
        return $this->general(user: $user, project: $project); //Чтобы не повторять код (принцип всё равно не отличается)
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Blacklist  $blacklist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Blacklist $blacklist)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Blacklist  $blacklist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Blacklist $blacklist)
    {
        return $this->general(user: $user, project: $blacklist->project); //Чтобы не повторять код (принцип всё равно не отличается)
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Blacklist  $blacklist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Blacklist $blacklist)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Blacklist  $blacklist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Blacklist $blacklist)
    {
        //
    }
}
