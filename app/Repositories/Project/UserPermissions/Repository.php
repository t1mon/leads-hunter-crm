<?php

namespace App\Repositories\Project\UserPermissions;

use App\Models\Project\Project;
use App\Models\Project\UserPermissions;
use App\Models\Role;
use App\Models\User;

class Repository{
    public function create(
        User $user,
        Project $project,
        string $role,
        array $fields = [],
    ): UserPermissions
    {
        return UserPermissions::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'role' => $role,
            'view_fields' => $fields,
        ]);
    } //create

    public function assignManager(User $user, Project $project): UserPermissions
    {
        return $this->create(user: $user, project: $project, role: Role::ROLE_MANAGER);
    } //assignManager

    public function assignJuniorManager(User $user, Project $project): UserPermissions
    {
        return $this->create(user: $user, project: $project, role: Role::ROLE_JUNIOR_MANAGER);
    } //assignJuniorManager

    public function assignWatcher(User $user, Project $project, array $fields = []): UserPermissions
    {
        return $this->create(user: $user, project: $project, role: Role::ROLE_WATCHER, fields: $fields);
    } //assignWatcher

    public function update(
        UserPermissions $userPermissions,
        string $role,
        array $fields = null,
    ): UserPermissions
    {
        $userPermissions->update([
            'role' => $role,
            'view_fields' => is_null($fields) ? $userPermissions->fields : $fields,
        ]);

        return $userPermissions;
    } //update

    public function changeRole(UserPermissions $userPermissions, string $role, array $fields = null): UserPermissions
    {
        return $this->update(userPermissions: $userPermissions, role: $role, fields: $fields ?? $userPermissions->view_fields);

    } //changeRole

    public function remove(UserPermissions $userPermissions): void
    {
        $userPermissions->delete();
    } //remove

};

?>