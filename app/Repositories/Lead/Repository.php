<?php

namespace App\Repositories\Lead;

use App\Http\Requests\Api\LeadsRequest;
use App\Models\Leads;
use App\Models\Project\Project;
use Carbon\Carbon;

class Repository{
    public function query()
    {
        return Leads::query();
    } //query

    public function create(
        Project $project,
        string $name,
        int $phone,
        ?string $host,
        ?string $surname,
        ?string $patronymic,
        ?string $owner,
        ?string $cost,
        ?string $email,
        ?string $comment,
        ?string $city,
        // ?string $region,
        ?string $manual_region,
        ?string $company,
        ?string $ip,
        ?string $referrer,
        ?string $source,
        ?string $utm_medium,
        ?string $utm_campaign,
        ?string $utm_source,
        ?string $utm_term,
        ?string $utm_content,
        ?string $url_query_string,
        ?string $nextcall_date,
    ): Leads
    {
        //Создание лида
        $lead = $this->query()->create([
            'project_id' => $project->id,
            'owner' => $owner,
            'name' => $name,
            'surname' => $surname,
            'patronymic' => $patronymic,
            'phone' => $phone,
            'entries' => $this->calculateEntries(project: $project, phone: $phone),
            'email' => $email,
            'cost' => $cost,
            'comment' => $comment,
            'city' => $city,
            // 'region' => $region,
            'manual_region' => $manual_region,
            'company' => $company,
            'ip' => $ip,
            'referrer' => $referrer,
            'source' => $source,
            'utm_medium' => $utm_medium,
            'utm_source' => $utm_source,
            'utm_campaign' => $utm_campaign,
            'utm_content' => $utm_content,
            'utm_term' => $utm_term,
            'host' => $host,
            'url_query_string' => $url_query_string,
            'nextcall_date' => is_null($nextcall_date) ? null : Carbon::parse(time: $nextcall_date, tz: $project->timezone)->setTimezone(config('app.timezone')),
        ]);

        //Заполнение остальных полей
        $lead->update([
            'full_name' => $lead->getClientName(),
            'utm' => [
                'utm_medium' => $utm_medium,
                'utm_source' => $utm_source,
                'utm_campaign' => $utm_campaign,
                'utm_content' => $utm_content,
                'utm_term' => $utm_term,
            ],
        ]);

        return $lead;
    } //create

    public function calculateEntries(Project|int $project, string $phone): int //Посчитать количество вхождений для лида
    {
        $lead = $this->query()->from($project)->phone($phone)->latest()->with('project')->first();
        if(is_null($lead))
            return 1;
        
        if($project->settings['leadValidDays'] > 0)
        {
            $leadDate = Carbon::parse($lead->created_at)->addDays($project->settings['leadValidDays']);
            if(Carbon::now()->greaterThanOrEqualTo($leadDate))
                return 1;
        }
        else
            return $lead->entries + 1;
    } //calculateEntries

    public function detectSource(LeadsRequest $request): string //Определить источника
    {
        //Если реферер не обнаружен, вернуть соответствующую запись
        if($request->exists('referrer') && ( parse_url($request->referrer,  PHP_URL_HOST) !== parse_url($request->host,  PHP_URL_HOST) ) ){
            return parse_url($request->referrer,  PHP_URL_HOST);
        }

        if(!$request->exists('url_query_string'))
            return Leads::SOURCE_DIRECT_ENTRY;

        $utm = [];
        parse_str(parse_url($request->url_query_string, PHP_URL_QUERY), $utm);

        if( array_key_exists('utm_source', $utm) )
            return $utm['utm_source'];
        else
            return Leads::SOURCE_DIRECT_ENTRY;
    } //detectSource

    public function getUTM(LeadsRequest $request): array //Получить UTM-метки в виде массива
    {
        $src = $request->exists('url_query_string')
                ? $request->url_query_string
                : ( $request->exists('referrer') ? $request->referrer : null);

        if(is_null($src))
            return [];

        $vars = [];
        parse_str(parse_url($request->url_query_string, PHP_URL_QUERY), $vars);

        $utm = [];

        foreach(['utm_source', 'utm_campaign', 'utm_medium', 'utm_term', 'utm_content'] as $utm_mark){
            if( array_key_exists($utm_mark, $vars) )
                $utm[$utm_mark] = $vars[$utm_mark];
        }

        return $utm;

    } //getUTM

    public function update(Leads $lead, array $params): Leads
    {
        $lead->update($params);
        return $lead;
    } //update

    public function remove(Leads $lead)
    {
        $lead->delete();
    } //remove

    public function makeFullNamesForProject(Project|int $project, bool $nullOnly = true) //Заполнить поле full_name в лидах по проекту
    {
        $leads = $this->query()
            ->from($project)
            ->when($nullOnly, function($query){ //Опция nullOnly указывает, что надо загрузить только лиды с пустым full_name
                return $query->whereNotNull('full_name');
            })
            ->chunkById(500, function($leads){
                $leads->each(function($lead){
                    $lead->update(['full_name' => $lead->getClientName()]);
                });
            });

    } //makeFullNames

    public static function makeFullNameForLead(Leads $lead) //Заполнить поле full_name у лида. Используется для старых API, где ещё не используется метод create из данного репозитория
    {
        $lead->update(['full_name' => $lead->getClientName()]);
    } //makeFullNameForLead

    public function splitUTMForProject(Project|int $project) //Взять значения из json-поля utm и записать их в отдельные поля
    {
        $this->query()
            ->from($project)
            ->whereNotNull('utm')
            ->where('utm', '!=', '[]')
            ->chunkById(500, function($leads){
                $leads->each(function($lead){
                    $lead->update([
                        'utm_medium' => $lead->utm['utm_medium'] ?? null,
                        'utm_source' => $lead->utm['utm_source'] ?? null,
                        'utm_campaign' => $lead->utm['utm_campaign'] ?? null,
                        'utm_content' => $lead->utm['utm_content'] ?? null,
                        'utm_term' => $lead->utm['utm_term'] ?? null,
                    ]);
                });
            });
    } //splitUTMForProject

    public static function splitUTMForLead(Leads $lead) //Заполнить отдельные поля UTM у лида. Используется для старых API, где ещё не используется метод create из данного репозитория
    {
        if(!is_null($lead->utm))
            $lead->update([
                'utm_medium' => $lead->utm['utm_medium'] ?? null,
                'utm_source' => $lead->utm['utm_source'] ?? null,
                'utm_campaign' => $lead->utm['utm_campaign'] ?? null,
                'utm_content' => $lead->utm['utm_content'] ?? null,
                'utm_term' => $lead->utm['utm_term'] ?? null,
            ]);
    } //splitUTMForLead

    public function addManualRegion(Leads $lead, string $region): void
    {
        $lead->update(['manual_region' => $region]);
    } //addManualRegion

    public function clearManualRegion(Leads $lead): void
    {
        $lead->update(['manual_region' => null]);
    } //clearManualRegion

    public function addNextCallDate(Leads $lead, string $datetime): void //Добавить дату следующего звонка
    {
        $lead->update(['nextcall_date' => Carbon::parse(time: $datetime, tz: $lead->project->timezone)->setTimezone(config('app.timezone'))]);
    } //addNextCallDate

    public function clearNextCallDate(Leads $lead): void //Удалить дату следующего звонка
    {
        $lead->update(['nextcall_date' => null]);
    } //clearNextCallDate
};

?>