<?php

namespace App\Mail\Leads;

use App\Events\Leads\LeadCreated;
use App\Models\Leads;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendLeadData extends Mailable
{
    use Queueable, SerializesModels;

    public Leads $lead;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Leads $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), config('app.name', 'Hunter'))
                    ->subject(__('leads.email.subject'))
                    ->view('emails.lead.data')
                    ->with([
                        'lead' => $this->lead
                    ]);
    }
}