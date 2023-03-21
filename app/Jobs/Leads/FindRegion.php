<?php

namespace App\Jobs\Leads;

use App\Models\Leads;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Lead\Repository as LeadRepository;

class FindRegion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private Leads $lead,
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $repository = app(LeadRepository::class);
        $project = $this->lead->project;
        
        if($project->find_region){
            $code = $repository->findRegion(lead: $this->lead);

            if($code === LeadRepository::STATUS_CONNECTION_ERROR)
                dispatch(new self($this->lead))
                    ->delay(now()->addMinutes(5));
        }
    }
}
