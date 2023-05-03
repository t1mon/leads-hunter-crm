<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadAdded;
use App\Events\Leads\LeadCreated;
use App\Events\Leads\LeadExists;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Repositories\Lead\Repository as LeadRepository;

class GetRegionFromPreviousLead
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
     * @param  \App\Events\Leads\LeadExists  $event
     * @return void
     */
    public function handle(LeadExists|LeadCreated|LeadAdded $event)
    {
        //Не посылать запрос, если отключена глобальная настройка
        if(!env('REGION_SERVICE_ENABLED', false))
            return;

        $this->repository->getRegionFromPreviousLead(lead: $event->lead);
    }
}
