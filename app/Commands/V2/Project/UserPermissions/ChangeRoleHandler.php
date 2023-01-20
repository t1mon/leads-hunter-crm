<?php

namespace App\Commands\V2\Project\UserPermissions;

use App\Repositories\Project\UserPermissions\Repository as PermissionsRepository;
use App\Repositories\Project\UserPermissions\ReadRepository as PermissionsReadRepository;

class ChangeRoleHandler
{
    /**
     * ChangeRoleHandler constructor.
     */
    public function __construct(
        private PermissionsRepository $permissionsRepository,
        private PermissionsReadRepository $permissionsReadRepository,
    )
    {
    }

    /**
     * @param ChangeRoleCommand $command
     */
    public function handle(ChangeRoleCommand $command)
    {
        $permissions = $this->permissionsReadRepository->findById(id: $command->request->permissions_id, fail: true);

        $this->permissionsRepository->changeRole(
            userPermissions: $permissions,
            role: $command->request->role,
            fields: $command->request->fields,
        );

        return response(content: 'Полномочия пользователя обновлены');
    }
}
