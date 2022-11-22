<?php

namespace App\Commands\V2\Project\Journal;

use App\Models\Project\UserPermissions;
use App\Models\User;
use App\Repositories\Lead\ReadRepository as LeadRepository;
use App\Repositories\Project\UserPermissions\ReadRepository as UserPermissionsRepository;

class GetVariantsHandler
{
    public function __construct(
        private LeadRepository $leadRepository,
        private UserPermissionsRepository $permissionsRepository,
    )
    {
    } //Конструктор

    /**
     * @param GetVariantsCommand $command
     */
    public function handle(GetVariantsCommand $command)
    {
        $permissions = $this->permissionsRepository->findByCurrentUserInProject(project: $command->project);
        return $this->leadRepository->getFilterVariants(project: $command->project, permissions: $permissions);
    }
}
