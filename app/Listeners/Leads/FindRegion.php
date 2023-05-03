<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadAdded;
use App\Events\Leads\LeadCreated;
use App\Events\Leads\LeadExists;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Repositories\Lead\Repository as LeadRepository;

class FindRegion implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private LeadRepository $repository
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
    public function handle(LeadCreated|LeadAdded|LeadExists $event)
    {
        //Не посылать запрос, если отключена глобальная настройка
        if(!env('REGION_SERVICE_ENABLED', false))
            return;

        $project = $event->lead->project;
        
        if($project->find_region)
            $this->repository->findRegion(lead: $event->lead);
    }
}
