<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
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
    public function handle(LeadCreated $event)
    {
        $this->repository->findRegion(lead: $event->lead);
    }
}
