<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadAdded;
use App\Events\Leads\LeadCreated;
use App\Events\Leads\LeadExists;
use App\Jobs\Leads\FindRegion as FindRegionJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Repositories\Lead\Repository as LeadRepository;

class FindRegion
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
        dispatch(new FindRegionJob($event->lead));
    }
}
