<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

use App\Journal\Facade\Journal;

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
        if(count( $event->lead->project->webhooks_active() )){
            $lead = $event->lead;
            $project = $lead->project;

            foreach($project->webhooks_active() as $webhook){
                $response = $project->webhook_send($webhook->name, $lead);
                if ($response->failed()) {
                    Journal::leadError($lead, $project, "Попытка отправки WebHook {$webhook->name} Status Code {$response->status()} for url {$webhook->url}");
                    Log::channel('leads')->error("ProjectId:{$lead->project->id} Попытка отправки WebHook {$webhook->name} Status Code {$response->status()} for url {$webhook->url}");
                    throw new \Exception("ProjectId:{$lead->project->id} Попытка отправки WebHook {$webhook->name} Status Code {$response->status()} for url {$webhook->url}");
                } else {
                    Journal::lead($lead, $project, "Отправлен WebHook {$webhook->name} по URL:{$webhook->url} Имя проекта {$lead->project->name} Идентификатор лида:{$lead->id}");
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
