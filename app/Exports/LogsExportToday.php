<?php

namespace App\Exports;

use App\Models\Project\Project;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Journal\Facade\Journal;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class LogsExportToday implements FromCollection
{
    use Exportable;

    public function today(Project $project){
        $this->project = $project;
        $this->entries = Journal::todayInProject($project);
        return $this;
    }

    public function all(Project $project){
        $this->project = $project;
        $this->entries = Journal::allInProject($project);
        return $this;
    }

    public function collection()
    {
        //Форматирование записей
        $formatted = [];
        foreach($this->entries as $entry){
            //Базовые поля
            $row = [
                Carbon::parse($entry->date, $this->project->timezone)->format('d.m.Y H:i:s'),
                trans('logs.action.'.$entry->action),
                trans('logs.class.'.$entry->class),
                property_exists($entry, 'user') ? $entry->user->name : trans('logs.unauthorized-user'),
            ];

            //Форматирование в зависмости от типа записи
            switch($entry->action){
                case 'project':
                    $row[] = $entry->project->name;
                    break;
                case 'lead':
                    $row[] = $entry->project->name;
                    if(property_exists($entry->lead, 'id'))
                        $row[] = trans('logs.lead-no'.$entry->lead->id);
                    $row[] = "{$entry->lead->name}, {$entry->lead->phone}";
            }
            
            $row[] = $entry->text;
            $formatted[] = [$row];
        }
        return collect($formatted);
    }
}
