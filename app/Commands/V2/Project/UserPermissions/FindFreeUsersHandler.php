<?php

namespace App\Commands\V2\Project\UserPermissions;

use App\Http\Resources\V2\Project\FreeUser;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Repositories\User\ReadRepository as UserReadRepository;

class FindFreeUsersHandler
{
    /**
     * FindFreeUsersHandler constructor.
     */
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
        private UserReadRepository $userReadRepository,
    )
    {
    }

    /**
     * @param FindFreeUsersCommand $command
     */
    public function handle(FindFreeUsersCommand $command)
    {
        $project = $this->projectReadRepository->findById(id: $command->request->project_id, fail: true);
        $users = $this->userReadRepository->findFreeUsersForProject($project);

        return FreeUser::collection($users);
    }
}
