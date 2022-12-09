<?php

namespace App\Commands\V2\Project\Journal;

use App\Exports\V2\Project\Journal\LeadExport;
use App\Models\Project\Project;
use App\Repositories\Project\UserPermissions\ReadRepository as UserPermissionsRepository;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;


class ExportHandler
{
    private const FILE_FORMAT = \Maatwebsite\Excel\Excel::XLSX; //Формат выгружаемого файла

    public function __construct(
        private ProjectReadRepository $projectReadRepository,
        private LeadReadRepository $leadReadRepository,
        private UserPermissionsRepository $permissionsRepository,
    )
    {
    } //Конструктор

    public function handle(ExportCommand $command)
    {
        //Загрузка проекта
        $project = $this->projectReadRepository->findById(id: $command->project, fail: true, with: 'classes');

        //Загрузка разрешений пользователя
        if($command->user->cannot('export', $project))
            return response('Unauthorized', Response::HTTP_FORBIDDEN);
        
        $permissions = $this->permissionsRepository->findByUserInProject(
            user: $command->user,
            project: $project
        );
    
        //Загрузка лидов
        $leads = $this->loadLeads(project: $project, command: $command);

        //Экспорт
        $exported = (new LeadExport)->make(project: $project, leadsQuery: $leads, user: $command->user, permissions: $permissions);

        //Выгрузка
        //...

        return response()->json([
            'message' => 'Команда отработала нормально',
            // 'data' => $exported,
        ]);
    } //handle

    private function loadLeads(Project $project, ExportCommand $command): Builder
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
        );

        return $query;
    } //loadLeads

    private function makeFileName(Project $project): string //Составление названия файла
    {
        $datetime = Carbon::now()->setTimezone($project->timezone)->format('d.m.Y h:i');
        return "{$datetime} {$project->name}." . self::FILE_FORMAT;
    } //makeFileName
}
