<?php

namespace App\Policies\V2\Project\Integrations;

use App\Models\Project\Integrations\EmailReader;
use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EmailReaderPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): bool|null
    {
        return $user->isAdmin() ? true : null;
    } //before

    public function all(User $user, Project $project) //Метод одинаковый, чтобы не повторять код
    {
        $permissions = $user->getPermissionsForProject($project);
        if(is_null($permissions))
            return Response::deny(message: 'Вы не имеете доступа к этому проекту');
        
        return $permissions->isOwner() || $permissions->isManager()
            ? Response::allow()
            : Response::deny('У вас отсутствуют полномочия для этого действия');
    } //general

    public function viewAny(User $user, Project $project)
    {
        return $this->all(user: $user, project: $project);
    } //viewAny

    public function view(User $user, EmailReader $emailReader)
    {
        return $this->all(user: $user, project: $emailReader->project);

    } //view

    public function create(User $user, Project $project)
    {
        return $this->viewAny(user: $user, project: $project);
    } //create

    public function update(User $user, EmailReader $emailReader)
    {
        return $this->all(user: $user, project: $emailReader->project);
    } //update

    public function delete(User $user, EmailReader $emailReader)
    {
        return $this->all(user: $user, project: $emailReader->project);
    } //delete

    public function restore(User $user, EmailReader $emailReader)
    {
        return $this->all(user: $user, project: $emailReader->project);
    } //restore

    public function forceDelete(User $user, EmailReader $emailReader)
    {
        return $this->all(user: $user, project: $emailReader->project);
    } //forceDelete
}
