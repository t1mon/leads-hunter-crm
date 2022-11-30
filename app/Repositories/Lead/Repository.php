<?php

namespace App\Repositories\Lead;

use App\Models\Leads;
use App\Models\Project\Project;

class Repository{
    public function query()
    {
        return Leads::query();
    } //query

    public function create()
    {

    } //create

    public function update()
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
};

?>