<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSMSData
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
    public function handle(LeadCreated $event)
    {
        if($event->lead->project->settings['SMS']['enabled']){
            $response = Http::get('https://sms.ru/sms/send', [
                'api_id' => 'D08E8C73-ED7D-835C-B9FA-9905C657B685',
                'to' => preg_replace("/[^,.0-9]/", '', $event->lead->phone),
                'msg' => $event->lead->project->settings['SMS']['text'],
                'json' => 1
            ]);
            Log::info("SMS: ".$response);
        }
    }
}
