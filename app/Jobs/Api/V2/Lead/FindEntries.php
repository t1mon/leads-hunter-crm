<?php

namespace App\Jobs\Api\V2\Lead;

use App\Events\Leads\LeadAdded;
use App\Events\Leads\LeadCreated;
use App\Events\Leads\LeadExists;
use App\Journal\Facade\Journal;
use App\Models\Leads;
use App\Models\Project\Project;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FindEntries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Leads $lead,
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Поиск дублей
        $previousLead = Leads::where('id', '!=', $this->lead->id)
            ->where(['project_id' => $this->lead->project_id, 'phone' => $this->lead->phone])
            ->latest()
            ->first();
        
        // Если дубли отсутствуют, запустить событие "Новый лид"
        if(is_null($previousLead)){
            Journal::lead($this->lead, 'Добавлен новый лид');
            LeadAdded::dispatch($this->lead);
            LeadCreated::dispatch($this->lead);
            return;
        }

        // Если дубли есть, проверить настройки проекта и посчитать количество вхождений
        $project = Project::where('id', $this->lead->project_id)->select(['id', 'settings'])->firstOrFail();

        if($project->settings['leadValidDays'] > 0)
        {
            $leadDate = Carbon::parse($previousLead->created_at)->addDays($project->settings['leadValidDays']);
            if(Carbon::now()->greaterThanOrEqualTo($leadDate)){
                Journal::lead($this->lead, 'Добавлен новый лид');
                LeadAdded::dispatch($this->lead);
                LeadCreated::dispatch($this->lead);
                return;
            }
        }

        $this->lead->update(['entries' => $previousLead->entries + 1, 'status' => Leads::LEAD_EXISTS]);
        Journal::lead($this->lead, 'Лид уже существует в базе (кол-во вхождений: '  . $this->lead->entries . ')');
        LeadAdded::dispatch($this->lead);
        LeadExists::dispatch($this->lead);
    }
}
