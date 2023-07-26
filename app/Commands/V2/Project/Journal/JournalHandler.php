<?php

namespace App\Commands\V2\Project\Journal;

use App\Models\Project\Project;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use App\Repositories\Project\UserPermissions\ReadRepository as UserPermissionsRepository;
use App\Http\Resources\V2\Project\Project\Journal as ProjectResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Response;

class JournalHandler
{
    private const PER_PAGE = 50;

    private User $user;

    /**
     * JournalHandler constructor.
     */
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
        private LeadReadRepository $leadReadRepository,
        private UserPermissionsRepository $permissionsRepository,
    )
    {}

    /**
     * @param JournalCommand $command
     */
    public function handle(JournalCommand $command)
    {
        //Загрузка проекта
        $project = $this->projectReadRepository->findById(id: $command->project_id, fail: true, with: 'classes');

        //Загрузка разрешений пользователя
        if($command->user->cannot('view', $project))
            return response('Unauthorized', Response::HTTP_FORBIDDEN);
        
        $permissions = $this->permissionsRepository->findByUserInProject(
            user: $command->user,
            project: $project
        );

        //Загрузка лидов
        $leads = $this->_loadLeads(project: $project, command: $command);

        return new ProjectResource(resource: $project, user: $command->user, permissions: $permissions, leads: $leads);
    } //handle

    private function _loadLeads(Project $project, JournalCommand $command)
    {
        $leads = $this->leadReadRepository->findFromProject(
            project: $project,
            date_from: $command->date_from,
            date_to: $command->date_to,
            class: $command->class,
            name: $command->name,
            entries: $command->entries,
            owner: $command->owner,
            phone: $command->phone,
            email: $command->email,
            cost_from: $command->cost_from,
            cost_to: $command->cost_to,
            city: $command->city,
            referrer: $command->referrer,
            source: $command->source,
            utm_medium: $command->utm_medium,
            utm_source: $command->utm_source,
            utm_campaign: $command->utm_campaign,
            utm_content: $command->utm_content,
            utm_term: $command->utm_term,
            host: $command->host,
            url_query_string: $command->url_query_string,
            sort_by: $command->sort_by,
            sort_order: $command->sort_order,
        );

        $leads = $leads->paginate(self::PER_PAGE);
        
        return $leads;
    } //_loadLeads
}
