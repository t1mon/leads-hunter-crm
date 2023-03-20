<?php

namespace App\Policies\V2;

use App\Models\Leads;
use App\Models\Project\Project;
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
     * @param  \App\Models\Project\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Project $project)
    {
        if($user->isAdmin())
            return Response::allow();

        $permissions = $user->getPermissionsForProject($project);
        if(is_null($permissions))
            return Response::deny(message: 'У вас нет доступа к этому проекту', code: HttpResponse::HTTP_FORBIDDEN);
        else
            return ($permissions->isOwner() || $permissions->isManager())
                ? Response::allow()
                : Response::deny(message: 'У вас нет полномчий на добавление лидов', code: HttpResponse::HTTP_FORBIDDEN);
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
     * Общая политика на добавление пользователем дополнительной информации к лиду
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leads  $leads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function setAdditionalInfo(User $user, Leads $lead)
    {
        if($user->isAdmin())
            return Response::allow();

        return $user->isInProject($lead->project)
            ? Response::allow()
            : Response::deny(message: 'У вас нет доступа к этому проекту', code: HttpResponse::HTTP_FORBIDDEN);
    } //setAdditionalInfo

    /**
     * Определить, может ли пользователь устанавливать дату следующего звонка
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leads  $leads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function setNextcall(User $user, Leads $lead)
    {
        if($user->isAdmin())
            return Response::allow();

        return $user->isInProject($lead->project)
            ? Response::allow()
            : Response::deny(message: 'У вас нет доступа к этому проекту', code: HttpResponse::HTTP_FORBIDDEN);
    } //setNextcall

    /**
     * Определить, может ли пользователь устанавливать регион лида вручную
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leads  $leads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function setManualRegion(User $user, Leads $lead)
    {
        if($user->isAdmin())
            return Response::allow();

        return $user->isInProject($lead->project)
            ? Response::allow()
            : Response::deny(message: 'У вас нет доступа к этому проекту', code: HttpResponse::HTTP_FORBIDDEN);
    } //setManualRegion

    /**
     * Определить, может ли пользователь указывать компанию для лида
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leads  $leads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function setCompany(User $user, Leads $lead)
    {
        //Используется общая функция, однако при необходимости здесь может быть указана индивидуальная политика
        return $this->setAdditionalInfo(user: $user, lead: $lead);
    } //setCompany
}
