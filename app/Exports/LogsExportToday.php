<?php

namespace App\Exports;

use App\Models\Project\Project;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Journal\Facade\Journal;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class LogsExportToday implements FromQuery
{
    use Exportable;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function query()
    {
        $entries = Journal::todayInProject($this->project);

        //Форматирование записей
        $formatted = [];
        foreach($entries as $entry){
            //Базовые поля
            $row = [
                Carbon::parse($entry->date, $this->project->timezone),
                $entry->action,
                $entry->class,
                property_exists($entry, 'user') ? $entry->user->name : 'Неавторизовавшийся пользователь',
            ];

            //Форматирование в зависмости от типа записи
            switch($entry->action){
                case 'project':
                    $row[] = $entry->project->name;
                    break;
                case 'lead':
                    $row[] = $entry->project->name;
                    if(property_exists($entry->lead, 'id'))
                        $row[] = "Лид №{$entry->lead->id}";
                    $row[] = "{$entry->lead->name}, {$entry->lead->phone}";
            }
            
            $row[] = $entry->text;
            $formatted[] = [$row];
        }
        return collect($formatted);
    }
}
