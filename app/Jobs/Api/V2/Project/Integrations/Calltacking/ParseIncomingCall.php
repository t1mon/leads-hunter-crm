<?php

namespace App\Jobs\Api\V2\Project\Integrations\Calltacking;

use App\Journal\Facade\Journal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Repositories\Project\Repository as ProjectRepository;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Repositories\Project\Integrations\Calltracking\Phone\Repository as PhoneRepository;
use App\Repositories\Project\Integrations\Calltracking\Phone\ReadRepository as PhoneReadRepository;
use App\Repositories\Lead\Repository as LeadRepository;
use App\Repositories\Project\Host\ReadRepository as HostReadRepository;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class ParseIncomingCall implements ShouldQueue
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
    )
    {
        $this->params = json_decode($this->params)[0];

        try{
            //Поиск номера трекинга
            $phone = $phoneReadRepository->findByPhone(phone: $this->params['caller_did'], fail: true, with: 'project');

            //Проверка проекта
            if(!$phone->project->settings['enabled']){
                Journal::projectError($phone->project, 'ПРОЕКТ ОТКЛЮЧЕН! Поступило уведомление с коллтрекинга по номеру ' . $this->params['caller_did'] . ', телефон лида ' . $this->params['caller_id']);
                return;
            }

            //Проверка хоста
            $host = filter_var(value: $this->params['url'], filter: FILTER_VALIDATE_URL)
                ? parse_url(url: $this->params['url'])['host']
                : $this->params['url'];

            $query = $hostReadRepository->query()
                ->where(['project_id' => $phone->project_id, 'host' => $host]);

            if(!$query->exists()){
                Journal::projectError($phone->project, 'ХОСТ ' . $host . ' НЕ НАЙДЕН! Поступило уведомление с коллтрекинга по номеру ' . $this->params['caller_did'] . ', телефон лида ' . $this->params['caller_id']);
                return;
            }

            //Создание лида
            $leadRepository->create(
                project: $phone->project,
                name: 'CALL_TRACKING',
                phone: $this->params['caller_id'],
                host: $host,
                surname: null,
                patronymic: null,
                owner: null,
                cost: null,
                email: null,
                comment: 'CALL_TRACKING: ' . $this->params['caller_did'],
                city: null,
                manual_region: null,
                company: null,
                ip: null,
                referrer: null,
                source: $this->params['source'] ?? null,
                utm_medium: $this->params['utm_medium'] ?? null,
                utm_term: $this->params['utm_term'] ?? null,
                utm_campaign: $this->params['utm_campaign'] ?? null,
                utm_source: $this->params['utm_source'] ?? null,
                utm_content: $this->params['utm_content'] ?? null,
                url_query_string: $this->params['url'] ?? null,
                nextcall_date: null
            );
        }
        catch(ModelNotFoundException $e){

        }
    }
}
