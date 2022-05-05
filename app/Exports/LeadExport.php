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

    public function asOfDate(Project $project, $date_from = null, $date_to = null){
        $this->project = $project;
        $leads = $this->project->leads();

        //Отсеивание по дате
        if(!is_null($date_from))
            $leads->where('created_at', '>=', $date_from);

        if(!is_null($date_to))
            $leads->where('created_at', '<=', $date_to);

        $leads = $leads->orderBy('created_at', 'desc')->get();

        $this->leads = $leads;
        $this->permissions = Auth::user()->getPermissionsForProject($project);
        return $this;
    }

    public function collection()
    {
        //Форматирование записей
        $formatted = [];
        $row = [];

        //Добавление заголовков
        //Базовые поля
        $row[] = 'Дата';
        $row[] = 'Проект';
        $row[] = 'Класс';
        $row[] = 'Клиент';
        $row[] = 'Телефон';
        $row[] = 'Кол-во вхождений';

        //Поля в зависимости от разрешений пользователя
        if($this->project->isOwner() or Auth::user()->isManagerFor($this->project)){
            $row[] = 'E-mail';
            $row[] = 'Сумма';
            $row[] = 'Комментарий';
            $row[] = 'Город';
            $row[] = 'Посадочная';
            $row[] = 'Источник';
            $row[] = 'UTM source';
            $row[] = 'cid';
            $row[] = 'campaign_id';
            $row[] = 'source_type';
            $row[] = 'UTM medium';
            $row[] = 'referrer';
        }
        else{
            foreach($this->permissions->view_fields as $field)
                $row[] = $lead->$field;
        }
        $formatted[] = [$row];

        //Добавление записей
        foreach($this->leads as $lead){
            $row = [];

            //Базовые поля
            $row[] = Carbon::parse($lead->updated_at)->setTimezone($this->project->timezone)->format('d.m.Y H:i:s');
            $row[] = $this->project->name;
            $row[] = $lead->class->name ?? null;
            $row[] = $lead->getClientName();
            $row[] = $lead->phone;
            $row[] =  $lead->entries;

            //Поля в зависимости от разрешений пользователя
            if($this->project->isOwner() or Auth::user()->isManagerFor($this->project)){
                $row[] = $lead->email;
                $row[] = $lead->cost;
                $row[] = $lead->comment;
                $row[] = $lead->city;
                $row[] = $lead->host;
                $row[] = $lead->source;
                $row[] = $lead->utm['utm_source'] ?? '';

                $tmp = explode('|', $lead->utm['utm_campaign'] ?? '');
                if(count($tmp)){
                    $row[] = $tmp[0] ?? '';
                    $row[] = $tmp[1] ?? '';
                    $row[] = $tmp[2] ?? '';
                }
                else{
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                }

                $row[] = $lead->utm['utm_medium'] ?? '';
                $row[] = $lead->referrer;
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
