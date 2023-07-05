<?php

namespace App\Repositories\Lead;

use App\Models\Leads;
use App\Models\Project\Project;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ReadRepository{
    
    public function query(): Builder
    {
        return Leads::query();
    } //query

    public function findFromProject(
        Project|int $project,
        $date_from = null,
        $date_to = null,
        $nextcall_from = null,
        $nextcall_to = null,
        $class = null,
        $name = null,
        $entries = null,
        $owner = null,
        $phone = null,
        $email = null,
        $cost_from = null,
        $cost_to = null,
        $city = null,
        $company = null,
        $region = null,
        $manual_region = null,
        $referrer = null,
        $source = null,
        $utm_medium = null,
        $utm_source = null,
        $utm_campaign = null,
        $utm_content = null,
        $utm_term = null,
        $host = null,
        $url_query_string = null,
        $sort_by = null,
        $sort_order = 'asc',
    ): Builder
    {
        $leads = $this->query()
            ->with(['comment_crm', 'class:id'])
            ->from($project);

        //Фильтрация по дате
        if(!is_null($date_from)){
            $date = Carbon::parse($date_from, $project->timezone)->startOfDay()->setTimezone(config('app.timezone'));
            $leads->where('created_at', '>=', $date);
        }

        if(!is_null($date_to)){
            $end_date = Carbon::parse($date_to, $project->timezone)->endOfDay()->setTimezone(config('app.timezone'));
            $leads->where('created_at', '<=', $end_date);;
        }

        //Фильтрация по дате следующего звонка
        if(!is_null($nextcall_from)){
            $date = Carbon::parse($nextcall_from, $project->timezone)->startOfDay()->setTimezone(config('app.timezone'));
            $leads->where('nextcall_date', '>=', $date);
        }

        if(!is_null($nextcall_to)){
            $end_date = Carbon::parse($nextcall_to, $project->timezone)->endOfDay()->setTimezone(config('app.timezone'));
            $leads->where('nextcall_date', '<=', $end_date);;
        }

        //Фильтрация по классу
        if(!is_null($class))
            $leads->ofClass($class);

        //Фильтрация по имени
        if(!is_null($name))
            $leads->name($name);

        //Фильтрация по числу вхождений
        if(!is_null($entries))
            $leads->entries($entries);

        //Фильтрация по владельцу
        if(!is_null($owner))
            $leads->owner($owner);

        //Фильтрация по телефону
        if(!is_null($phone))
            $leads->phone($phone);

        //Фильтрация по email
        if(!is_null($email))
            $leads->email($email);
            
        //Фильтрация по сумме
        if(!is_null($cost_from))
            $leads->where('cost', '>=', $cost_from);
        
        if(!is_null($cost_to))
            $leads->where('cost', '<=', $cost_to);

        //Фильтрация по городу
        if(!is_null($city))
            $leads->city($city);

        //Фильтрация по компании
        if(!is_null($company))
            $leads->company($company);

        //Фильтрация по региону
        if(!is_null($region))
            $leads->region($region);

        //Фильтрация по региону
        if(!is_null($manual_region))
            $leads->manualRegion($manual_region);

        //Фильтрация по рефереру
        if(!is_null($referrer))
            $leads->referrer($referrer);
        
        //Фильтрация по источнику
        if(!is_null($source))
            $leads->source($source);
            
        //Фильтрация по UTM-меткам
        if(!is_null($utm_medium))
            $leads->utmMedium($utm_medium);

        if(!is_null($utm_source))
            $leads->utmSource($utm_source);

        if(!is_null($utm_campaign))
            $leads->utmCampaign($utm_campaign);

        if(!is_null($utm_content))
            $leads->utmContent($utm_content);
            
        if(!is_null($utm_term))
            $leads->utmContent($utm_term);
        
        //Фильтрация по хосту
        if(!is_null($host))
            $leads->host($host);

        //Фильтрация по query string
        if(!is_null($url_query_string))
            $leads->queryString($url_query_string);

        //Сортировка
        if(!is_null($sort_by))
            $leads->orderBy($sort_by, $sort_order);
        else
            // $leads->latest(); //по умолчанию сортировать по дате в порядке убывания
            $leads->orderByDesc('id');

        return $leads;
    } //findFromProject

    public function updateDataToTimezone(Leads $lead, Project $project): Carbon  //Обновить дату лида по часовому поясу проекта
    {
        return Carbon::parse($lead->created_at, config('app.timezone'))->setTimezone($project->timezone);
    } //updateDataToTimezone

    public function findById(int $id, bool $fail = false, array|string $with = null): ?Leads
    {
        $query = $this->query()
            ->where('id', $id)
            ->when(!is_null($with), function($query) use ($with){
                return $query->with($with);
            });

        return $fail ? $query->firstOrFail() : $query->first();
    } //findById
};

?>