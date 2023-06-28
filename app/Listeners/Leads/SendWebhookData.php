<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
use App\Models\Leads;
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
    public function __construct()
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
        // TODO Потом переписать с возможностью вкл\выкл, пока добавлено отключение напрямую от коллтрекинга
        if ($event->lead->source === Leads::SOURCE_CALL_TRACKING){
            return;
        }

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

        return false;
    }
}
