<?php

namespace App\Services\Api\V2\Project;

use App\Models\Project\Project;

use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Http\Requests\Api\V2\Project\Project\Journal as JournalRequest;
use App\Http\Resources\V2\Project\Project\Journal as JournalResource;
use App\Http\Resources\V2\Project\Project\Dashboard as DashboardResource;

use App\Models\User;

use App\Repositories\Project\UserPermissions\ReadRepository as UserPermissionsRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Service{
    private null|int $userId;

    public function __construct(
        private ProjectReadRepository $projectReadRepository
    )
    {
        $this->userId = Auth::guard('api')->id() ?? null;
    } //Конструктор

    //
    //  R-методы
    //
    public function dashboard()
    {
        $permissionsRepository = new UserPermissionsRepository;
        $ids = $permissionsRepository->findProjectIdsByUser($this->userId);

        $projects = $this->projectReadRepository->findByIdsWithLeadsCount_q(
            ids: $ids,
            select: [
                'projects.id', 'projects.name', 'projects.settings', 'projects.created_at',
                'leads_count.total_leads', 'leads_count.leads_today',
            ],
            perPage: 10
        );

        return DashboardResource::collection($projects);
    } //dashboard

    public function journal(Project|int $project, JournalRequest $request)
    {
        //Загрузка данных
        if(is_numeric($project))
            $project = $this->projectReadRepository->findById(id: $project, fail: true, with: 'leads');
            
        //
        // ...
        //
    } //journal
};

?>