<?php

namespace App\Commands\V2\Project\UserPermissions;

use App\Http\Resources\V2\Project\UserPermissions\IndexForProject;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;

class IndexHandler
{
    /**
     * IndexHandler constructor.
     */
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
    )
    {
        //
    } //Конструктор

    /**
     * @param IndexCommand $command
     */
    public function handle(IndexCommand $command)
    {
        $project = $this->projectReadRepository->findById(id: $command->request->project_id, fail: true, with: ['user_permissions', 'user_permissions.user']);

        return IndexForProject::collection($project->user_permissions);
    }
}
