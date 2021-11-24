<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWebhookData
{
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
        $lead = $event->lead;
        $project = $lead->project;

        foreach($project->webhooks as $webhook){
            if($webhook->enabled)
                $project->webhook_send($webhook->name, $lead);
        }
    }
}
