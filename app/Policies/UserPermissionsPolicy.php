<?php

namespace App\Policies;

use App\Models\Project\Project;
use App\Models\Project\UserPermissions;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Response as HttpResponse;

class UserPermissionsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Project  $project
     * @return mixed
     */
    public function viewAny(User $user, Project $project)
    {
        if($user->isAdmin())
            return Response::allow();

        $permissions = $user->getPermissionsForProject($project);
        if(is_null($permissions))
            return Response::deny(message: 'У вас нет доступа к этому проекту');

        if($permissions->isOwner() || $permissions->isManager())
            return Response::allow();
        else
            return Response::deny(message: 'У вас нет полномочий на это действие');
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

    public function create(User $user, Project $project)
    {
        if($user->isAdmin())
            return Response::allow();

        $permissions = $user->getPermissionsForProject($project);
        if(is_null($permissions))
            return Response::deny(message: 'У вас нет доступа к этому проекту');

        if($permissions->isOwner() || $permissions->isManager())
            return Response::allow();
        else
            return Response::deny(message: 'У вас нет полномочий на это действие');
    }

    public function update(User $user, UserPermissions $target)
    {
        if($target->isOwner()) //Владельца изменять нельзя
            return Response::deny(message: 'У владельца проекта не могут изменяться полномочия');

        if($user->isAdmin())
            return Response::allow();

        $permissions = $user->getPermissionsForProject($target->project);
        if(is_null($permissions))
            return Response::deny(message: 'У вас нет доступа к этому проекту');

        if($permissions->isOwner() || $permissions->isManager())
            return Response::allow();
        else
            return Response::deny(message: 'У вас нет полномочий на это действие');
    }

    public function delete(User $user, UserPermissions $target)
    {
        if($target->isOwner()) //Владельца удалять нельзя
            return Response::deny(message: 'Невозможно удалить владельца проекта');

        if($user->isAdmin())
            return Response::allow();

        $permissions = $user->getPermissionsForProject($target->project);
        if(is_null($permissions))
            return Response::deny(message: 'У вас нет доступа к этому проекту');

        if($permissions->isOwner() || $permissions->isManager())
            return Response::allow();
        else
            return Response::deny(message: 'У вас нет полномочий на это действие');
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
