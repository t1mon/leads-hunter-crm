<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadAdded;
use App\Events\Leads\LeadCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Repositories\Lead\Repository as LeadRepository;

class SplitUTM
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
     * @param  \App\Events\Leads\LeadCreated  $event
     * @return void
     */
    public function handle(LeadCreated|LeadAdded $event)
    {
        LeadRepository::splitUTMForLead($event->lead);
    }
}
