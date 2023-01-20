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
        $project = $this->projectReadRepository->findById(id: $command->request->project_id, fail: true);
        // $user = $this->userReadRepository->findById(id: $command->request->user_id, fail: true);

        foreach($command->request->users as $item){
            $user = $this->userReadRepository->find(user: $item['email'], fail: true);

            //Если пользователь уже назначен на проект, не назначать его
            if(!$user->isInProject($project)){
                //Назначение
                // $this->permissionsRepository->create(
                //     user: $user,
                //     project: $project,
                //     role: $command->request->role,
                //     fields: $command->request->filled('fields') ? $command->request->fields : [],
                // );
                $this->permissionsRepository->create(
                    user: $user,
                    project: $project,
                    role: $item['role'],
                    fields: $item['fields'] ?? [],
                );
            }
        }

        // return response(content: 'Пользователь назначен на проект', status: Response::HTTP_CREATED);
        return response(content: 'Пользователи назначены на проект', status: Response::HTTP_CREATED);
    }
}
