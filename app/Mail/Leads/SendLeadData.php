<?php

namespace App\Mail\Leads;

use App\Events\Leads\LeadCreated;
use App\Models\Project\Project;
use App\Models\Leads;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class SendLeadData extends Mailable
{
    use Queueable, SerializesModels;

    public Leads $lead;
    public $subject;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Leads $lead, string $subject, string $type = Project::TEMPLATE_VIEW)
    {
        $this->lead = $lead;
        $this->subject = $subject;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), config('app.name', 'Hunter'))
                    ->subject(empty($this->subject) ? __('leads.email.subject') : $this->subject)
                    ->markdown('emails.markdown.lead.data')
                    ->with([
                        'lead' => $this->lead,
                        'type' => $this->type,
                    ]);
    }
}
