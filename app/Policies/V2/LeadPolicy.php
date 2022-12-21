<?php

namespace App\Policies\V2;

use App\Models\Leads;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Response as HttpResponse;

class LeadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leads  $leads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Leads $leads)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leads  $leads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Leads $lead)
    {
        if($user->isAdmin())
            return Response::allow();

        $permissions = $user->getPermissionsForProject($lead->project);
        if(is_null($permissions))
            return Response::deny(message: 'У вас нет доступа к этому проекту', code: HttpResponse::HTTP_FORBIDDEN);
        else
            return ($permissions->isOwner() || $permissions->isManager())
                ? Response::allow()
                : Response::deny(message: 'У вас нет полномчий на изменение лидов', code: HttpResponse::HTTP_FORBIDDEN);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leads  $leads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Leads $lead)
    {
        if($user->isAdmin())
            return Response::allow();

        $permissions = $user->getPermissionsForProject($lead->project);
        if(is_null($permissions))
            return Response::deny(message: 'У вас нет доступа к этому проекту', code: HttpResponse::HTTP_FORBIDDEN);
        else
            return ($permissions->isOwner() || $permissions->isManager())
                ? Response::allow()
                : Response::deny(message: 'У вас нет полномчий на удаление лидов', code: HttpResponse::HTTP_FORBIDDEN);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leads  $leads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Leads $leads)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leads  $leads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Leads $leads)
    {
        //
    }

    /**
     * Определить, может ли пользователь устанавливать дату следующего звонка
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leads  $leads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function setNextcall(User $user, Leads $lead)
    {
        throw new \Exception(message: 'test');
        if($user->isAdmin())
            return Response::allow();

        return $user->isInProject($lead->project)
            ? Response::allow()
            : Response::deny(message: 'У вас нет доступа к этому проекту', code: HttpResponse::HTTP_FORBIDDEN);
    } //setNextcall
}
