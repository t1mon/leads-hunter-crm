<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;
use App\Journal\Facade\Journal;

use App\Mail\Leads\SendExportedLeads;
use App\Models\Project\Project;
use App\Models\Leads;
use App\Exports\LeadExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportLeadsToMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $project;
    public $email;
    public $date_from;
    public $date_to;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Project $project, string $email, $date_from = null, $date_to = null)
    {
        $this->project = $project;
        $this->email = $email;
        $this->date_from = $date_from;
        $this->date_to = $date_to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Составление названия файла
        $filename = (is_null($this->date_from)
                    ? Carbon::parse($this->project->leads->min('created_at'))->setTimezone($this->project->timezone)->format('d-m-Y')
                    : $this->date_from->setTimezone($this->project->timezone)->format('d-m-Y')) . '-' .
                    (is_null($this->date_to)
                    ? Carbon::parse($this->project->leads->max('created_at'))->setTimezone($this->project->timezone)->format('d-m-Y')
                    : $this->date_to->setTimezone($this->project->timezone)->format('d-m-Y ')) . ' ' . $this->project->name;
        
        //Создание файла
        $file = (new LeadExport)->asOfDate($this->project, $this->date_from, $this->date_to)
            ->download($filename.".".Maatwebsite\Excel\Excel::XLSX, Maatwebsite\Excel\Excel::XLSX);

        //Отправка файла на почту
        try {
            $subject = "Лиды по проекту \"{$this->project->name}\"";
            $message = (new SendExportedLeads($file, $filename, $subject))->onQueue('emails');
            Mail::to($this->email)->queue($message);
        } catch (\Exception $exception) {
            //Journal::projectError($this->project, $exception->getMessage());
            Log::channel('leads')->error($exception->getMessage());
        }   
    }
}
