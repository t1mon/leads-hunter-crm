<?php

namespace App\Commands\V2\Project\UserPermissions;

use App\Repositories\User\ReadRepository as UserReadRepository;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Repositories\Project\UserPermissions\Repository as PermissionsRepository;
use App\Repositories\Project\UserPermissions\ReadRepository as PermissionsReadRepository;
use Illuminate\Http\Response;

class AssignHandler
{
    /**
     * AssignHandler constructor.
     */
    public function __construct(
        private UserReadRepository $userReadRepository,
        private ProjectReadRepository $projectReadRepository,
        private PermissionsRepository $permissionsRepository,
        private PermissionsReadRepository $permissionsReadRepository,
    )
    {
    }

    /**
     * @param AssignCommand $command
     */
    public function handle(AssignCommand $command)
    {
        //Загрузка данных
        $user = $this->userReadRepository->findById(id: $command->request->user_id, fail: true);
        $project = $this->projectReadRepository->findById(id: $command->request->project_id, fail: true);
        
        //Если пользователь уже назначен на проект, вернуть ответ
        if($user->isInProject($project))
            return response(content: 'Пользователь уже назначен на этот проект', status: Response::HTTP_BAD_REQUEST);

        //Назначение
        $this->permissionsRepository->create(
            user: $user,
            project: $project,
            role: $command->request->role,
            fields: $command->request->filled('fields') ? $command->request->fields : [],
        );

        return response(content: 'Пользователь назначен на проект', status: Response::HTTP_CREATED);
    }
}
