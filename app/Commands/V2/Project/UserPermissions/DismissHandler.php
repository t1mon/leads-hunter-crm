<?php

namespace App\Commands\V2\Project\UserPermissions;

use App\Repositories\Project\UserPermissions\Repository as PermissionsRepository;
use App\Repositories\Project\UserPermissions\ReadRepository as PermissionsReadRepository;
use Illuminate\Http\Response;

class DismissHandler
{
    /**
     * DismissHandler constructor.
     */
    public function __construct(
        private PermissionsRepository $permissionsRepository,
        private PermissionsReadRepository $permissionsReadRepository,
    )
    {
    }

    /**
     * @param DismissCommand $command
     */
    public function handle(DismissCommand $command)
    {
        $permissions = $this->permissionsReadRepository->findById(id: $command->request->permissions_id, fail: true);
        $this->permissionsRepository->remove($permissions);

        return response(content: 'Назначение пользователя снято', status: Response::HTTP_NO_CONTENT);
    }
}
