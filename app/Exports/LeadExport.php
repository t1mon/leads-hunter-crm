<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
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
            $leads->where('created_at', '>=', Carbon::parse($date_from, $project->timezone)->startOfDay()->setTimezone(config('app.timezone')));

        if(!is_null($date_to))
            $leads->where('created_at', '<=', Carbon::parse($date_to, $project->timezone)->endOfDay()->setTimezone(config('app.timezone')));

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
            $row[] = 'UTM term';
            $row[] = 'UTM source';
            $row[] = 'UTM campaign';
            $row[] = 'UTM medium';
            $row[] = 'referrer';
        }
        else{
            foreach($this->permissions->view_fields as $field)
                $row[] = __('projects.journal.'.$field);
        }
        $formatted[] = [$row];

        //Добавление записей
        foreach($this->leads as $lead){
            $row = [];

            //Если номер телефона не указан в видимых полях, скрыть его
            $phone = $lead->phone;
            if($this->permissions->isWatcher($this->project) && !in_array('phone', $this->permissions->view_fields))
                $phone = '******' . substr($phone, 7);

            //Базовые поля
            $row[] = Carbon::parse($lead->created_at, config('app.timezone'))->setTimezone($this->project->timezone)->format('d.m.Y H:i:s');
            $row[] = $this->project->name;
            $row[] = $lead->class->name ?? null;
            $row[] = $lead->getClientName();
            $row[] = $phone;
            $row[] =  $lead->entries;

            //Поля в зависимости от разрешений пользователя
            if($this->project->isOwner() or Auth::user()->isManagerFor($this->project)){
                $row[] = $lead->email;
                $row[] = $lead->cost;
                $row[] = $lead->comment;
                $row[] = $lead->city;
                $row[] = $lead->host;
                $row[] = $lead->source;
                $row[] = $lead->utm_term;
                $row[] = $lead->utm_source;
                $row[] = $lead->utm_campaign;
                $row[] = $lead->utm_medium;
                // $row[] = $lead->utm['utm_term'] ?? '';
                // $row[] = $lead->utm['utm_source'] ?? '';
                // $row[] = $lead->utm['utm_campaign'] ?? '';
                // $row[] = $lead->utm['utm_medium'] ?? '';
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
