<?php

namespace App\Commands\V2\Project\Export;

use App\Exports\V2\Project\Journal\LeadExport;
use App\Jobs\V2\Project\Export\GenerateExportFile;
use App\Models\Project\Export;
use App\Models\Project\Project;
use App\Repositories\Project\UserPermissions\ReadRepository as UserPermissionsRepository;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;

class StartExportHandler
{
    /**
     * StartExportHandler constructor.
     */
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
        private LeadReadRepository $leadReadRepository,
        private UserPermissionsRepository $permissionsRepository,
    )
    {
    }

    /**
     * @param StartExportCommand $command
     */
    public function handle(StartExportCommand $command)
    {
        //Загрузка проекта
        $project = $this->projectReadRepository->findById(id: $command->project, fail: true, with: 'classes');

        $permissions = $this->permissionsRepository->findByUserInProject(
            user: $command->user,
            project: $project
        );
    
        //Загрузка лидов
        $leads = $this->_loadLeads(project: $project, command: $command);

        //Запуск задачи по экспорту на фоне
        dispatch(
            new GenerateExportFile(
                project: $project,
                leads: $leads,
                user: $command->user,
                permissions: $permissions,
            )
        );

        return response(content: 'Начат экспорт. Вы получите уведомление, когда файл будет готов');
    }

    private function _loadLeads(Project $project, StartExportCommand $command)
    {
        //Первичный запрос
        $query = $this->leadReadRepository->findFromProject(
            project: $project,
            date_from: $command->request->date_from,
            date_to: $command->request->date_to,
            class: $command->request->class,
            name: $command->request->name,
            entries: $command->request->entries,
            owner: $command->request->owner,
            phone: $command->request->phone,
            email: $command->request->email,
            cost_from: $command->request->cost_from,
            cost_to: $command->request->cost_to,
            city: $command->request->city,
            referrer: $command->request->referrer,
            source: $command->request->source,
            utm_medium: $command->request->utm_medium,
            utm_source: $command->request->utm_source,
            utm_campaign: $command->request->utm_campaign,
            utm_content: $command->request->utm_content,
            utm_term: $command->request->utm_term,
            host: $command->request->host,
            url_query_string: $command->request->url_query_string,
            sort_by: $command->request->sort_by,
            sort_order: $command->request->sort_order,
        )
        ->with('class')
        ->get();

        return $query;
    } //loadLeadsgit
}
