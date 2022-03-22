<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
use App\Journal\Facade\Journal;
use App\Services\Sms\SmsSender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\Middleware\ThrottlesExceptions;


class SendSMSData implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'sms';
    //public $tries = 5;
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $sender;

    public function __construct(SmsSender $sender)
    {
        $this->sender = $sender;
    }

//    public function middleware()
//    {
//        return [(new ThrottlesExceptions(1, 2))->backoff(1)];
//    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Leads\LeadCreated  $event
     * @return void
     */
    public function handle(LeadCreated $event)
    {
        if($event->lead->project->settings['SMS']['enabled']){

            $response = $this->sender->send($event->lead->phone, $event->lead->project->settings['SMS']['text']);

            $response = json_decode($response);

            Log::channel('leads')->info("send sms to ". $event->lead->phone . " --> STATUS " . $response->status);
            Journal::lead($event->lead, 'Отправлено смс на номер ' . $event->lead->phone ." --> STATUS " . $response->status);
        }
    }

    /**
     * Определить, следует ли ставить слушателя в очередь.
     *
     * @param  \App\Events\Leads\LeadCreated  $event
     * @return bool
     */
    public function shouldQueue(LeadCreated $event)
    {
        $project = $event->lead->project;

        return $project->settings['SMS']['enabled'];
    }

//    public function failed()
//    {
//        //Log::error('failed!');
//        //$this->release(10);
//
//    }

//    public function retryUntil()
//    {
//        return now()->addMinutes(3);
//    }

}
