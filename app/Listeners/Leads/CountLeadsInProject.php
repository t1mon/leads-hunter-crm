<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
use App\Events\Leads\LeadDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Repositories\Project\ReadRepository as ProjectRepository;

class CountLeadsInProject implements ShouldQueue
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

    public function handle(LeadCreated|LeadDeleted $event)
    {
        ProjectRepository::countTotalLeads(project_id: $event->lead->project_id);
        ProjectRepository::countLeadsToday(project_id: $event->lead->project_id);
    }
}
