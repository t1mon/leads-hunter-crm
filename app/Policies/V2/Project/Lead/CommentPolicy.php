<?php

namespace App\Policies\V2\Project\Lead;

use App\Models\Project\Lead\Comment;
use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Response as HttpResponse;

class CommentPolicy
{
    use HandlesAuthorization;

    public function __construct(
        //
    )
    {
        
    } //Конструктор

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user, Project $project)
    {
        return $user->isAdmin() || $user->isInProject($project);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Lead\Comment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Comment $comment)
    {
        //Если пользователь является администратором, он может просматривать все комментарии
        if($user->isAdmin())
            return Response::allow();

        //Если пользователь добавлен в проект, проверить его полномочия
        $permissions = $user->getPermissionsForProject($comment->project);

        if(is_null($permissions))
            return Response::deny(message: 'Вы не имеете доступа к этому проекту');
        else{
            if($permissions->isOwner() || $permissions->isManager())
                return Response::allow();

            return $permissions->fieldAllowed('comment_crm')
                ? Response::allow()
                : Response::deny(message: 'Вы не имеете доступа к комментариям');
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Project $project)
    {
        if($user->isAdmin())
            return Response::allow();

        $permissions = $user->getPermissionsForProject($project);
        if(is_null($permissions))
            return Response::deny(message: 'Вы не имеете доступа к этому проекту');
        else
            return ($permissions->isOwner() || $permissions->isManager() )
                ?   Response::allow()
                :   Response::deny(message: 'У вас нет полномочий добавлять комментарии');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Lead\Comment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Comment $comment)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Lead\Comment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Comment $comment)
    {
        if($user->isAdmin())
            return Response::allow();

        $permissions = $user->getPermissionsForProject($comment->project);
        if(is_null($permissions))
            return Response::deny(message: 'Вы не имеете доступа к этому проекту');
        else
            return ($permissions->isOwner() || $permissions->isManager() )
                ?   Response::allow()
                :   Response::deny(message: 'У вас нет полномочий удалять комментарии');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Lead\Comment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Comment $comment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project\Lead\Comment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Comment $comment)
    {
        //
    }
}
