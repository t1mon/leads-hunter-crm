<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\Project\Project;
use App\Models\Leads;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class LeadExport implements FromCollection
{
    use Exportable;

    public function today(Project $project){
        $this->project = $project;
        $this->leads = $project->leadsToday();
        $this->permissions = Auth::user()->getPermissionsForProject($project);
        return $this;
    }

    public function collection()
    {
        //Форматирование записей
        $formatted = [];
        foreach($this->leads as $lead){
            //Базовые поля
            $row = [
                Carbon::parse($lead->updated_at)->setTimezone($this->project->timezone)->format('d.m.Y H:i:s'),
                $this->project->name,
                $lead->class->name ?? null,
                $lead->getClientName(),
                $lead->phone,
                $lead->entries,
            ];

            //Поля в зависимости от разрешений пользователя
            if($this->project->isOwner() or Auth::user()->isManagerFor($this->project)){
                $row[] = $lead->email;
                $row[] = $lead->cost;
                $row[] = $lead->comment;
                $row[] = $lead->city;
                $row[] = $lead->host;
                $row[] = $lead->source;
                $row[] = $lead->utm;
            }
            else{
                foreach($this->permissions->view_fields as $field)
                    $row[] = $lead->$field;
            }

            $formatted[] = [$row];
        }

        return collect($formatted);
    }
}
