<?php

namespace App\Commands\V2\Lead\Accept;

use App\Http\Resources\V2\Leads\AcceptUserList;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Repositories\Project\UserPermissions\ReadRepository as PermissionsReadRepository;

class GetUsersForProjectHandler
{
    /**
     * GetUsersForProjectHandler constructor.
     */
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
        private PermissionsReadRepository $permissionsReadRepository,
    )
    {
    }

    /**
     * @param GetUsersForProjectCommand $command
     */
    public function handle(GetUsersForProjectCommand $command)
    {
        $project = $this->projectReadRepository->findById(id: $command->request->project_id, fail: true);

        //Если пользователь младший менеджер, сразу вернуть его данные
        if($command->request->user()->isJuniorManagerFor($project)){
            $permissions = $this->permissionsReadRepository->findByUserInProject(user: $command->request->user(), project: $project);
            $permissions->load('user');
            return new AcceptUserList($permissions);
        }
        
        $permissions = $project->user_permissions()->with('user')->get();

        return AcceptUserList::collection($permissions);
    }
}
