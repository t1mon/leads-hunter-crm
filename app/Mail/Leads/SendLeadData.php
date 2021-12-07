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

        switch ($this->type) {
            case Project::TEMPLATE_MARKDOWN:
                $from = env('MAIL_FROM_ADDRESS') ;
                $sendName = config('app.name', 'Hunter');
                break;
            case Project::TEMPLATE_VIEW:
                $from = env('MAIL_FROM_ADDRESS2') ;
                $sendName = env('MAIL_SEND_NAME2');
                break;
            default:
                $from = env('MAIL_FROM_ADDRESS2') ;
                $sendName = env('MAIL_SEND_NAME2');
        }

        return $this->from($from, $sendName)
                    ->subject(empty($this->subject) ? __('leads.email.subject') : $this->subject)
                    ->markdown('emails.markdown.lead.data')
                    ->with([
                        'lead' => $this->lead,
                        'type' => $this->type,
                    ]);
    }
}
