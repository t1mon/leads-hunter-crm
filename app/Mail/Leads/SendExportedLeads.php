<?php

namespace App\Mail\Leads;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendExportedLeads extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($exportedFile, string $filename, string $subject)
    {
        $this->exportedFile = $exportedFile;
        $this->filename = $filename;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("example@mail.com")->subject($this->subject)->view('emails.html.lead.export')->attachData($this->exportedFile, $this->filename);
    }
}
