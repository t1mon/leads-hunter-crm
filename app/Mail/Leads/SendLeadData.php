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
                $type = Project::TEMPLATE_MARKDOWN;
                $template = Project::TEMPLATE_MARKDOWN;
                break;
            case Project::TEMPLATE_VIEW:
                $from = env('MAIL_FROM_ADDRESS2') ;
                $sendName = env('MAIL_SEND_NAME2');
                $type = Project::TEMPLATE_VIEW;
                $template = 'html';
                break;
            case Project::TEMPLATE_TEXT:
                $from = env('MAIL_FROM_ADDRESS2') ;
                $sendName = env('MAIL_SEND_NAME2');
                $type = Project::TEMPLATE_TEXT;
                $template = Project::TEMPLATE_TEXT;
                break;
            default:
                $from = env('MAIL_FROM_ADDRESS2') ;
                $sendName = env('MAIL_SEND_NAME2');
                $type = Project::TEMPLATE_MARKDOWN;
                $template = Project::TEMPLATE_MARKDOWN;
        }

        return $this->from($from, $sendName)
                    ->subject(empty($this->subject) ? __('leads.email.subject') : $this->subject)
                    ->$type("emails.$template.lead.data")
                    ->with([
                        'lead' => $this->lead,
                        'type' => $this->type,
                    ]);
    }
}
