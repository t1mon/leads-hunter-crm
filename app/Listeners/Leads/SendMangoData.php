<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
use App\Journal\Facade\Journal;
use App\Services\Project\Integrations\MangoService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMangoData
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private MangoService $service
    )
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Leads\LeadCreated  $event
     * @return void
     */
    public function handle(LeadCreated $event)
    {
        $lead = $event->lead;
        $project = $event->lead->project;
        $integrations = $this->service->findByProjectId(project: $project->id);
        
        if($integrations->isNotEmpty()){
            $integrations->each(function($item, $key) use ($lead){
                if($item->enabled){
                    $response = $item->sendLead($lead);
                    $response->throw();
                    Journal::lead(lead: $lead, text: 'Лид отправлен на интеграцию Mango Office: ' . $item->name);
                }   
            });
        }
    }
}
