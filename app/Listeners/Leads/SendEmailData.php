<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
use App\Mail\Leads\SendLeadData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailData implements ShouldQueue
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
     * @param LeadCreated $event
     * @return void
     */
    public function handle(LeadCreated $event)
    {
        //Рассылка по e-mail
        if($event->lead->project->settings['email']['enabled']){
            $emails = $event->lead->project->emails;
            foreach($emails as $email){
                try {
                    Mail::to($email->email)->send(new SendLeadData($event->lead));
                    Log::channel('leads')->info(json_encode($event->lead)." --> ".$email->email);
                    Log::channel('leads')->info("Lead id:".$event->lead->id." sent to ".$email->email);
                } catch (\Exception $exception) {
                    Log::channel('leads')->error($exception->getMessage());
                }
            }
        }
    }
}
