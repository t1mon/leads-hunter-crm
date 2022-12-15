<?php

namespace App\Repositories\Lead;

use App\Models\Leads;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests\Api\V2\Project\Project\Journal as JournalRequest;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ReadRepository{
    
    public function query(): Builder
    {
        return Leads::query();
    } //query

    public function findFromProject(Project|int $project, JournalRequest $request): QueryBuilder
    {
        $leads = $this->query()
            ->with(['comment_crm', 'class:id'])
            ->from($project);

        //Фильтрация по дате
        if(!is_null($request->date_from)){
            $date = Carbon::parse($request->date_from, $project->timezone)->startOfDay()->setTimezone(config('app.timezone'));
            $leads->where('created_at', '>=' ,$date);
        }

        if(!is_null($request->date_to)){
            $end_date = Carbon::parse($request->date_to, $project->timezone)->endOfDay()->setTimezone(config('app.timezone'));
            $leads->where('created_at', '<=' ,$end_date);;
        }

        //Фильтрация по классу
        if(!is_null($request->class))
            $leads->ofClass($request->class);

        //Фильтрация по имени
        if(!is_null($request->name))
            $leads->name($request->name);

        //Фильтрация по числу вхождений
        if(!is_null($request->entries))
            $leads->entries($request->entries);

        //Фильтрация по владельцу
        if(!is_null($request->owner))
            $leads->owner($request->owner);

        //Фильтрация по телефону
        if(!is_null($request->phone))
            $leads->phone($request->phone);

        //Фильтрация по email
        if(!is_null($request->email))
            $leads->email($request->email);
            
        //Фильтрация по сумме
        if(!is_null($request->cost_from))
            $leads->where('cost', '>=', $request->cost_from);
        
        if(!is_null($request->cost_to))
            $leads->where('cost', '<=', $request->cost_to);

        //Фильтрация по городу
        if(!is_null($request->city))
            $leads->city($request->city);

        //Фильтрация по рефереру
        if(!is_null($request->referrer))
            $leads->referrer($request->referrer);
        
        //Фильтрация по источнику
        if(!is_null($request->source))
            $leads->source($request->source);
            
        //Фильтрация по UTM-меткам
        if(!is_null($request->utm_medium))
            $leads->utmMedium($request->utm_medium);

        if(!is_null($request->utm_source))
            $leads->utmSource($request->utm_source);

        if(!is_null($request->utm_campaign))
            $leads->utmCampaign($request->utm_campaign);

        if(!is_null($request->utm_content))
            $leads->utmContent($request->utm_content);
            
        if(!is_null($request->utm_term))
            $leads->utmContent($request->utm_term);
        
        //Фильтрация по хосту
        if(!is_null($request->host))
            $leads->host($request->host);

        //Фильтрация по query string
        if(!is_null($request->url_query_string))
            $leads->queryString($request->url_query_string);

        //Сортировка
        if(!is_null($request->sort_by))
            $leads->orderBy($request->sort_by, $request->sort_order);
        else
            $leads->latest(); //по умолчанию сортировать по дате в порядке убывания

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
            ->when(!is_null($with), function($query){
                return $query->with($with);
            });

        return $fail ? $query->firstOrFail() : $query->first();
    } //findById
};

?>