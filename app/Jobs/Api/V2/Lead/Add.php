<?php

namespace App\Jobs\Api\V2\Lead;

use App\Events\Leads\LeadAdded;
use App\Events\Leads\LeadCreated;
use App\Events\Leads\LeadExists;
use App\Http\Requests\Api\LeadsRequest;
use App\Journal\Facade\Journal;
use App\Models\Leads;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Repositories\Project\Host\ReadRepository as HostReadRepository;
use App\Repositories\Lead\Repository as LeadRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Add implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        // private LeadsRequest $request,
        private array $data,
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
        HostReadRepository $hostReadRepository,
        LeadRepository $leadRepository,
    )
    {
        //Загрузка проекта
        $project = $projectReadRepository->findByApiToken(api_token: $this->data['api_token'], fail: true);
        if(!$project->enabled){
            Journal::projectWarning($project, 'Попытка добавить лид в выключенный проект');
            return;
        }

        //Предварительная обработка данных
        $phone = $this->data['phone'];
        if ($phone[0] == 8) 
            $phone = preg_replace('/^./','7', $phone);


        $host = $this->data['host'];
        if(filter_var($host, FILTER_VALIDATE_URL)){
            $parsed = parse_url($host);
            $host = Str::lower($parsed['host']);
        }
        if(!$hostReadRepository->isInProject(project: $project, host: $host)){
            Journal::projectError($project, "Попытка добавить лид по отсутствующей посадочной (посадочная $host не указана в проекте");
            return;
        }

        // $utm = $leadRepository->getUTM($this->request); //TODO Скорректировать функцию для определения UTM
        $utm = [];

        //Создание лида
        $newLead = $leadRepository->create(
            project: $project,
            name: $this->data['name'],
            phone: $phone,
            host: $host,
            surname: Arr::get(array: $this->data, key: 'surname'),
            patronymic: Arr::get(array: $this->data, key: 'patronymic'),
            owner: Leads::OWNER_API,
            cost: preg_replace("/[^0-9]/", '', trim(Arr::get(array: $this->data, key: 'cost'))),
            email: Arr::get(array: $this->data, key: 'email'),
            comment: Arr::get(array: $this->data, key: 'comment'),
            city: Arr::get(array: $this->data, key: 'city'),
            // region: null, //TODO решить вопрос автоматического выставления региона
            manual_region: Arr::get(array: $this->data, key: 'manual_region'),
            company: Arr::get(array: $this->data, key: 'company'),
            ip: Arr::get(array: $this->data, key: 'ip'),
            referrer: Arr::get(array: $this->data, key: 'referrer'),
            source: null /*$leadRepository->detectSource($this->request)*/, //TODO Скорректировать функцию для определения источников
            utm_medium: Arr::get(array: $utm, key: 'utm_medium') ?? Arr::get(array: $this->data, key: 'utm_medium'),
            utm_campaign: Arr::get(array: $utm, key: 'utm_campaign') ?? Arr::get(array: $this->data, key: 'utm_campaign'),
            utm_source: Arr::get(array: $utm, key: 'utm_source') ?? Arr::get(array: $this->data, key: 'utm_source'),
            utm_term: Arr::get(array: $utm, key: 'utm_term') ?? Arr::get(array: $this->data, key: 'utm_term'),
            utm_content: Arr::get(array: $utm, key: 'utm_content') ?? Arr::get(array: $this->data, key: 'utm_content'),
            url_query_string: Arr::get(array: $this->data, key: 'url_query_string'),
            nextcall_date: null,
        );
        event(new LeadAdded($newLead));

        if($newLead->entries === 1) //Если лид новый, сделать рассылку
        {
            Journal::lead($newLead, 'Добавлен лид');
            event(new LeadCreated($newLead));
        }
        else{
            Journal::lead($newLead, 'Добавлен уже имеющийся лид');
            event(new LeadExists($newLead));
        }   
    }
}
