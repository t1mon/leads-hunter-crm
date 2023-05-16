<?php

namespace App\Commands\V2\Lead\CUD;

use App\Journal\Facade\Journal;
use App\Models\Project\Project;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Repositories\Project\Host\ReadRepository as HostReadRepository;
use App\Repositories\Lead\Repository as LeadRepository;

class AddHandler
{
    /**
     * AddHandler constructor.
     */
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
        private HostReadRepository $hostReadRepository,
        private LeadRepository $leadRepository,
    )
    {
    }

    /**
     * @param AddCommand $command
     */
    public function handle(AddCommand $command)
    {
        //Загрузка проекта
        if(!$command->project instanceof Project)
            $command->project = $this->projectReadRepository->findById(id: $command->project, fail: true);
        
        //Проверка проекта
        if(!$command->project->settings['enabled']){
            Journal::projectError($command->project, 'ПРОЕКТ ОТКЛЮЧЕН! Поступило уведомление с коллтрекинга по номеру ' . $this->params['caller_did'] . ', телефон лида ' . $this->params['caller_id']);
            return;
        }

        //Проверка хоста
        $host = filter_var(value: $command->host, filter: FILTER_VALIDATE_URL)
        ? parse_url(url: $command->host)['host']
        : $command->host;

        if(!$this->hostReadRepository->validateHost(project: $command->project, host: $host)){
            Journal::projectError($command->project, 'ХОСТ ' . $host . ' НЕ НАЙДЕН! Поступило уведомление с коллтрекинга по номеру ' . $this->params['caller_did'] . ', телефон лида ' . $this->params['caller_id']);
            return;
        }

        //Создание лида            
        $lead = $this->leadRepository->add(
            project: $command->project,
            name: 'CALL_TRACKING',
            phone: $command->phone,
            comment: $command->comment,
            source: $command->source,
            utm_medium: $command->utm_medium,
            utm_term: $command->utm_term,
            utm_campaign: $command->utm_campaign,
            utm_source: $command->utm_source,
            utm_content: $command->utm_content,
            url_query_string: $command->url_query_string,
        );

        Journal::lead($lead, 'Добавлен лид');
    }
}
