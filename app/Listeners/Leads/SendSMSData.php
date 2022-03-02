<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
use App\Journal\Facade\Journal;
use App\Services\Sms\SmsSender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSMSData implements ShouldQueue
{
    public $queue = 'sms';
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
}
