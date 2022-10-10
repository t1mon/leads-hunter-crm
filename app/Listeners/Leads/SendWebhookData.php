<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

use App\Journal\Facade\Journal;
use App\Services\Project\Integrations\MangoService;

class SendWebhookData implements ShouldQueue
{
    public $queue = 'webhook';
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private MangoService $mangoService
    )
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LeadCreated  $event
     * @return void
     */
    public function handle(LeadCreated $event)
    {
        if(count( $event->lead->project->webhooks_active() )){
            $lead = $event->lead;
            $project = $lead->project;

            foreach($project->webhooks_active() as $webhook){
                $response = $project->webhook_send($webhook->name, $event->lead);
                if ($response->failed()) {
                    Journal::leadError($lead, "Попытка отправки вебхука {$webhook->name} Код статуса {$response->status()} на url {$webhook->url}");
                    Journal::leadError($event->lead, "Ответ сервера {$response}");
                    Log::channel('leads')->error("ProjectId:{$lead->project->id} Попытка отправки WebHook {$webhook->name} Status Code {$response->status()} for url {$webhook->url}");
                    throw new \Exception("ProjectId:{$lead->project->id} Попытка отправки WebHook {$webhook->name} Status Code {$response->status()} for url {$webhook->url}");
                } else {
                    Journal::lead($event->lead, "Лид отправлен на вебхук {$webhook->name} по url {$webhook->url}");
                    Journal::lead($event->lead, "Ответ сервера {$response}");
                    Log::channel('leads')->info("ProjectId:{$lead->project->id} Отправлен WebHook {$webhook->name} по URL:{$webhook->url} Имя проекта {$lead->project->name} Идентификатор лида:{$lead->id}");
                }
            }
        }

        //Отправка в Mango Office
        $mangos = $this->mangoService->findByProjectId($event->lead->project_id);
        if( $mangos->isNotEmpty() ){
            foreach($mangos as $mango){
                if(!$mango->enabled)
                    continue;

                $response = $mango->sendLead($event->lead);
                if($response->ok())
                    Journal::lead(lead: $event->lead, text: 'Лид отправлен на интеграцию Mango Office ' . $mango->name . '. Ответ сервера: ' . $response);
                elseif($response->failed())
                    Journal::leadError(lead: $event->lead, text: 'Ошибка отправления лида на интеграцию Mango Office ' . $mango->name . '. Ответ сервера: ' . $response);
            }
        }
    }
    /**
     * Определить, следует ли ставить слушателя в очередь.
     *
     * @param  \App\Events\Leads\LeadCreated  $event
     * @return bool
     */
    public function shouldQueue(LeadCreated $event)
    {
        if (!is_array($event->lead->project->webhooks)) return false;
        foreach($event->lead->project->webhooks as $webhook){
            if($webhook->enabled)
                return true;
        }

        //Отправка на Mango Office
        $mangos = $this->mangoService->findByProjectId($event->lead->project_id);
        if($mangos->isEmpty())
            return false;
        
        foreach($mangos as $mango)
            if($mango->enabled)
                return true;

        return false;
    }
}
