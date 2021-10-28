<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
use App\Mail\Leads\SendLeadData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailData
    //implements ShouldQueue
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
        Log::channel('leads')->info(json_encode($event));
        if($event->lead->project->notifications_enabled){
            $emails = $event->lead->project->emails;
            foreach($emails as $email){
                Log::channel('leads')->info('Отправлено уведомление по ' . $email->email);
            }
        }
        else
        Log::channel('leads')->info('У проекта ' . $event->lead->project->name . ' отключены уведомления.');


//        try {
//            Mail::to('gorin163@gmail.com')->send(new SendLeadData($event->lead));
//            Log::channel('leads')->info(json_encode($event));
//        } catch (\Exception $exception) {
//            Log::error($exception->getMessage());
//        }
    }
}
