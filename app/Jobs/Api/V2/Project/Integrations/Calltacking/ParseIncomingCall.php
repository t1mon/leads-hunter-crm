<?php

namespace App\Jobs\Api\V2\Project\Integrations\Calltacking;

use App\Journal\Facade\Journal;
use App\Models\Leads;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Repositories\Project\Integrations\Calltracking\Phone\ReadRepository as PhoneReadRepository;
use App\Repositories\Lead\Repository as LeadRepository;
use App\Repositories\Project\Host\ReadRepository as HostReadRepository;
use App\Repositories\Project\Integrations\Calltracking\Log\Repository as LogRepository;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ParseIncomingCall
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public $params
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        ProjectReadRepository $projectReadRepository,
        PhoneReadRepository $phoneReadRepository,
        HostReadRepository $hostReadRepository,
        LeadRepository $leadRepository,
        LogRepository $logRepository,
    )
    {
        $originalJson = $this->params; //Сохраняем оригинальный JSON для записи в лог
        $this->params = json_decode(json: $this->params, associative: true)[0];

        try{
            //Поиск номера трекинга
            $phone = $phoneReadRepository->findByPhone(phone: $this->params['caller_did'], fail: true, with: 'project');


            //Загрузка проекта
            $project = $projectReadRepository->findById(id: $phone->project_id, fail: true);

            //Проверка проекта
            if(!$project->settings['enabled']){
                Journal::projectError($project, 'ПРОЕКТ ОТКЛЮЧЕН! Поступило уведомление с коллтрекинга по номеру ' . $this->params['caller_did'] . ', телефон лида ' . $this->params['caller_id']);
                return;
            }

            //Проверка хоста
            $host = filter_var(value: $this->params['url'], filter: FILTER_VALIDATE_URL)
                ? parse_url(url: $this->params['url'])['host']
                : $this->params['url'];

            if(!$hostReadRepository->validateHost(project: $project, host: $host)){
                Journal::projectError($project, 'ХОСТ ' . $host . ' НЕ НАЙДЕН! Поступило уведомление с коллтрекинга по номеру ' . $this->params['caller_did'] . ', телефон лида ' . $this->params['caller_id']);
                return;
            }

            //Создание лида
            $lead = $leadRepository->add(
                project: $project,
                name: 'Без имени',
                phone: $this->params['caller_id'],
                host: $host,
                comment: 'CALL_TRACKING: ' . $this->params['caller_did'],
                source: Leads::SOURCE_CALL_TRACKING,
                utm_medium: $this->params['utm_medium'] ?? null,
                utm_term: $this->params['utm_term'] ?? null,
                utm_campaign: $this->params['utm_campaign'] ?? null,
                utm_source: $this->params['utm_source'] ?? null,
                utm_content: $this->params['utm_content'] ?? null,
                url_query_string: $this->params['url_query_string'] ?? null,
            );

            //Запись в лог звонков
            $logRepository->create(
                project: $project,
                phone: $phone,
                json: $originalJson
            );
        }
        catch(ModelNotFoundException $e){
            Log::warning(
                message: 'CALL_TRACKING по номеру' . $this->params['caller_did'] . ' не подключен',
                context: $this->params
            );

            return response('CALL_TRACKING по номеру' . $this->params['caller_did'] . ' не подключен');
        }
    }
}
