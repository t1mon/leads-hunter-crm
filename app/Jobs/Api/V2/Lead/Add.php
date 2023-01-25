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
        private LeadsRequest $request,
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
        $project = $projectReadRepository->findByApiToken(api_token: $this->request->api_token, fail: true);
        if(!$project->enabled){
            Journal::projectWarning($project, 'Попытка добавить лид в выключенный проект');
            return;
        }

        //Предварительная обработка данных
        $phone = $this->request->phone;
        if ($phone[0] == 8) {
            $phone = preg_replace('/^./','7', $phone);
            $this->request->merge(['phone' => $phone]);
        }

        $host = $this->request->host;
        if(filter_var($host, FILTER_VALIDATE_URL)){
            $parsed = parse_url($host);
            $host = Str::lower($parsed['host']);
        }
        if(!$hostReadRepository->isInProject(project: $project, host: $host)){
            Journal::projectError($project, "Попытка добавить лид по отсутствующей посадочной (посадочная $host не указана в проекте");
            return;
        }

        $utm = $leadRepository->getUTM($this->request);

        //Создание лида
        $newLead = $leadRepository->create(
            project: $project->id,
            name: $this->request->name,
            phone: $phone,
            host: $host,
            surname: $this->request->surname,
            patronymic: $this->request->patronymic,
            owner: $this->request->user()?->name ?? Leads::OWNER_API,
            cost: preg_replace("/[^0-9]/", '', trim($this->request->cost)),
            email: $this->request->email,
            comment: $this->request->comment,
            city: $this->request->city,
            // region: null, //TODO решить вопрос автоматического выставления региона
            manual_region: $this->request->manual_region,
            company: $this->request->company,
            ip: $this->request->ip,
            referrer: $this->request->referrer,
            source: $leadRepository->detectSource($this->request),
            utm_medium: $utm['utm_medium'] ?? $this->request->utm_medium,
            utm_campaign: $utm['utm_campaign'] ?? $this->request->utm_campaign,
            utm_source: $utm['utm_source'] ?? $this->request->utm_source,
            utm_term: $utm['utm_term'] ?? $this->request->utm_term,
            utm_content: $utm['utm_content'] ?? $this->request->utm_content,
            url_query_string: $this->request->url_query_string,
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
