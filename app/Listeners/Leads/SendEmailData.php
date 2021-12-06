<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;
use App\Mail\Leads\SendLeadData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Journal\Facade\Journal;

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
            //Если рассылка всех лидов выключена, и количество вхождений превышает 1
            if(!$event->lead->project->settings['email']['send_all'] and $event->lead->entries > 1){
                Journal::leadWarning($event->lead, $event->lead->project, "Лид id:" . $event->lead->id . " не отправлен по Email ограничение числа вхождений лида entries > 1 ");
                Log::channel('leads')->warning("Лид id:" . $event->lead->id . " не отправлен по Email: ограничение числа вхождений лида entries > 1 ");
                return;
            }

            $emails = $event->lead->project->emails;
            foreach ($emails as $email) {
                try {
                    // $subject = 'Test_Subject';
                    $subject = $event->lead->project->settings['email']['subject'];
                    $message = (new SendLeadData($event->lead, $subject))->onQueue('emails');
                    Mail::to($email->email)->queue($message);
                    Log::channel('leads')->info(json_encode($event->lead) . " --> " . $email->email);

                    Journal::lead($event->lead, $event->lead->project, 'Лид №' . $event->lead->id . ' (' . $event->lead->name . ', ' . $event->lead->phone . ') отправлен на ' . $email->email);
                    Log::channel('leads')->info("Lead id:" . $event->lead->id . " sent to " . $email->email);
                } catch (\Exception $exception) {
                    Journal::leadError($event->lead, $event->lead->project, $exception->getMessage());
                    Log::channel('leads')->error($exception->getMessage());
                }
            }
        }
    }
}
