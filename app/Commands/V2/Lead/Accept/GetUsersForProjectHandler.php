<?php

namespace App\Commands\V2\Lead\Accept;

use App\Http\Resources\V2\Leads\AcceptUserList;
use App\Models\Role;
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

        //Список пользователей приходит согласно положению запрашивающего (по нисходящей)
        $user = $command->request->user();
        $userPermissions = $this->permissionsReadRepository->findByUserInProject(user: $command->request->user(), project: $project);

        // $permissions = $project->user_permissions()
        $list = $project->user_permissions()
            ->where('role', '!=', Role::ROLE_WATCHER)
            ->when($userPermissions->isManager(), function($query) use ($project){
                return $query->where('user_id', '!=', $project->user_id);
            })
            ->with('user')
            ->get();

        // return AcceptUserList::collection($permissions);
        return AcceptUserList::collection($list);
    }
}
