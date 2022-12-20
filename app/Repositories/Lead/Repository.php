<?php

namespace App\Repositories\Lead;

use App\Models\Leads;
use App\Models\Project\Project;
use Illuminate\Support\Str;

class Repository{
    public function query()
    {
        return Leads::query();
    } //query

    public function create(
        Project|int $project,
        string $name,
        int $phone,
        string $host,
        ?string $surname,
        ?string $patronymic,
        ?string $cost,
        ?string $comment,
        ?string $city,
        ?string $ip,
        ?string $email,
        ?string $utm_medium,
        ?string $utm_campaign,
        ?string $utm_source,
        ?string $utm_content,
        ?string $referrer,
        ?string $url_query_string,
    )
    {
        //Подготовка параметров
        $project = $project instanceof Project ? $project->id : $project;
        
        if(filter_var($host, FILTER_VALIDATE_URL))
            $host = Str::lower( parse_url($host) );

        if($phone[0] === 8)
            $phone = preg_replace('/^./','7', $phone);
        
        //TODO Спросить
        $cost = preg_replace("/[^0-9]/", '', trim($cost));

        //Создание лида
        $this->query()->create([
            'project_id' => $project,
            'name' => $name,
            'surname' => $surname,
            'patronymic' => $patronymic,
            //...
        ]);
    } //create

    public function update(Leads $lead, array $params)
    {

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
};

?>