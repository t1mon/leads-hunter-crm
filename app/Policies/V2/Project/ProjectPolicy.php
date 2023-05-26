<?php

namespace App\Policies\V2\Project;

use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }
    
        return null;
    }

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
     * @param  \App\Models\Project\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Project $project)
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
     * @param  \App\Models\Project\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Project $project)
    {        
        $permissions = $user->getPermissionsForProject($project);

        if(is_null($permissions))
            return Response::deny(message: 'Вы не имеете доступа к этому проекту');
        else
            if ($permissions->isOwner() || $permissions->isManager()) //Пользователь является владельцем или менеджером проекта
                return   Response::allow();
            else
                return Response::deny(message: 'Вы не имеете полномочий на внесение изменений в проект');
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
        //
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
        //
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
        //
    }

    /**
     * Проверяет, имеет ли пользователь доступ к указанной настройке
     * 
     * @param \App\Models\User $user
     * @param  \App\Models\Project\Project  $project
     * @param string  $settings   Настройка, к которой нужно проверить доступ
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function changeSettings(User $user, Project $project, string $settings)
    {
        $permissions = $user->getPermissionsForProject($project);
        
        if($permissions->isOwner($project) or $permissions->isManager($project))
            return true;
        
        return $permissions->settingsAllowed($settings);
    } //changeSettings
}
