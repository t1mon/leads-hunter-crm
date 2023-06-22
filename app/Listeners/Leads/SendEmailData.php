<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadAdded;
use App\Events\Leads\LeadCreated;
use App\Mail\Leads\SendLeadData;
use App\Models\Leads;
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
    public function handle(LeadCreated|LeadAdded $event)
    {
        //Рассылка по e-mail
        if($event->lead->project->settings['email']['enabled']){
            $calltraking = $event->lead->project->settings['calltracking_sync'] ?? false;

            if ($event->lead->source === Leads::SOURCE_CALL_TRACKING && !$calltraking){
                return;
            }

            $emails = $event->lead->project->emails;
            foreach ($emails as $email) {
                try {
                    // $subject = 'Test_Subject';
                    $subject = $event->lead->project->settings['email']['subject'];
                    $message = (new SendLeadData($event->lead, $subject, $event->lead->project->settings['email']['template']))->onQueue('emails');
                    Mail::to($email->email)->queue($message);
                    Log::channel('leads')->info(json_encode($event->lead) . " --> " . $email->email);

                    Journal::lead($event->lead, 'Лид отправлен на ' . $email->email);
                    Log::channel('leads')->info("Lead id:" . $event->lead->id . " sent to " . $email->email);
                } catch (\Exception $exception) {
                    Journal::leadError($event->lead, $exception->getMessage());
                    Log::channel('leads')->error($exception->getMessage());
                }
            }
        }
    }
}
