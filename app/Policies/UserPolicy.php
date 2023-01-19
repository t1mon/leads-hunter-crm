<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user is admin for all authorization.
     */
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the user.
     */
    public function update(User $current_user, User $user): bool
    {
        return $current_user->id === $user->id;
    }

    /**
     * Determine whether the user can generate a personnal access token.
     */
    public function api_token(User $current_user, User $user): bool
    {
        return $current_user->id === $user->id;
    }

    public function viewAny(User $user)
    {
        throw new \Exception(__METHOD__);

        if($user->isAdmin())
            return Response::allow();

        //Предусмотреть логику
        //...
        return Response::allow(); //Заглушка
    }
}
