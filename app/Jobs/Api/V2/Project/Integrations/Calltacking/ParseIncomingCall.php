<?php

namespace App\Jobs\Api\V2\Project\Integrations\Calltacking;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Repositories\Project\Integrations\Calltracking\Phone\ReadRepository as PhoneReadRepository;
use App\Repositories\Lead\Repository as LeadRepository;
use App\Repositories\Project\Host\ReadRepository as HostReadRepository;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class ParseIncomingCall implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public $params
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        ProjectReadRepository $projectReadRepository,
        PhoneReadRepository $phoneReadRepository,
        HostReadRepository $hostReadRepository,
        LeadRepository $leadRepository,

        CommandBusInterface $bus,
    )
    {
        $this->params = json_decode($this->params)[0];

        try{
            //Поиск номера трекинга
            $phone = $phoneReadRepository->findByPhone(phone: $this->params['caller_did'], fail: true, with: 'project');

            //Назначение команды
            $bus->addHandler(
                command: \App\Commands\V2\Lead\CUD\AddCommand::class,
                handler: \App\Commands\V2\Lead\CUD\AddHandler::class
            );

            //Выполнение команды
            $bus->dispatch(
                command: \App\Commands\V2\Lead\CUD\AddCommand::class,
                input: [
                    'project' => $phone->project,
                    'name' => 'CALL_TRACKING',
                    'phone' => $this->params['caller_id'],
                    'comment' => 'CALL_TRACKING: ' . $this->params['caller_did'],
                    'source' => $this->params['source'] ?? null,
                    'utm_medium' => $this->params['utm_medium'] ?? null,
                    'utm_term' => $this->params['utm_term'] ?? null,
                    'utm_campaign' => $this->params['utm_campaign'] ?? null,
                    'utm_source' => $this->params['utm_source'] ?? null,
                    'utm_content' => $this->params['utm_content'] ?? null,
                    'url_query_string' => $this->params['url'] ?? null,
                ]
            );
        }
        catch(ModelNotFoundException $e){

        }
    }
}
