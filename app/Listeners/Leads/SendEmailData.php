<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
use App\Mail\Leads\SendLeadData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailer;
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
        //Рассылка по e-mail
        if($event->lead->project->settings['email']['enabled']){
            if ($event->lead->entries === 1) {
                $emails = $event->lead->project->emails;
                foreach ($emails as $email) {
                    try {
                        $message = (new SendLeadData($event->lead))->onQueue('emails');
                        Mail::to($email->email)->queue($message);
                        Log::channel('leads')->info(json_encode($event->lead) . " --> " . $email->email);
                        Log::channel('leads')->info("Lead id:" . $event->lead->id . " sent to " . $email->email);
                    } catch (\Exception $exception) {
                        Log::channel('leads')->error($exception->getMessage());
                    }
                }
            }else{
                Log::channel('leads')->warning("Лид id:" . $event->lead->id . " не отправлен по Email ограничение числа вхождений лида entries > 1 ");
            }
        }
    }
}
