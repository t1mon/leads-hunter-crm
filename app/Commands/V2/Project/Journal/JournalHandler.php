<?php

namespace App\Commands\V2\Project\Journal;

use App\Models\Project\Project;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use App\Http\Resources\Project as ProjectResource;
use Carbon\Carbon;

class JournalHandler
{
    /**
     * JournalHandler constructor.
     */
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
        private LeadReadRepository $leadReadRepository,
    )
    {
    }

    /**
     * @param JournalCommand $command
     */
    public function handle(JournalCommand $command)
    {
        //Загрузка проекта
        $project = $this->projectReadRepository->findById(id: $command->project_id, fail: true, with: 'classes');

        //TODO Если в проекте нет лидов, не осуществлять запрос и сразу вернуть пустую коллекцию
        //...

        //Проверка полномочий пользователя
        // ...

        //Загрузка лидов
        $leads = $this->_loadLeads(project: $project, command: $command);

        return new ProjectResource($project, ['leads' => $leads, 'classes' => $project->classes]);
    } //handle

    private function _loadLeads(Project $project, JournalCommand $command)
    {
        $leads = $this->leadReadRepository->query()
            ->with('comment_CRM')
            ->from($command->project_id);

        //Фильтрация по дате
        if(!is_null($command->date_from)){
            $date = Carbon::parse($command->date_from, $project->timezone)->startOfDay()->setTimezone(config('app.timezone'));
            $leads->where('created_at', '>=' ,$date);
        }

        if(!is_null($command->date_to)){
            $end_date = Carbon::parse($command->date_to, $project->timezone)->endOfDay()->setTimezone(config('app.timezone'));
            $leads->where('created_at', '<=' ,$end_date);;
        }

        //Фильтрация по числу вхождений
        if(!is_null($command->owner))
            $leads->owner($command->owner);

        //Фильтрация по числу вхождений
        if(!is_null($command->entries))
            $leads->entries($command->entries);

        //Фильтрация по хосту
        if(!is_null($command->host))
            $leads->host($command->host);

        //Фильтрация по городу
        if(!is_null($command->city))
            $leads->city($command->city);

        //Фильтрация по источнику
        if(!is_null($command->source))
            $leads->source($command->source);

        //Фильтрация по сумме
        if(!is_null($command->cost_from))
            $leads->where('cost', '>=', $command->cost_from);

        if(!is_null($command->cost_to))
            $leads->where('cost', '<=', $command->cost_to);

        return $leads->paginate(50);
    } //_loadLeads
}
