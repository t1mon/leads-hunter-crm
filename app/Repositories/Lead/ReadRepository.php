<?php

namespace App\Repositories\Lead;

use App\Models\Leads;
use App\Models\Project\Project;
use App\Models\Project\UserPermissions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class ReadRepository{
    private const PER_PAGE = 50;

    public function query(): Builder
    {
        return Leads::query();
    }

    public function getFilterVariants(Project|int $project, ?UserPermissions $permissions) //Получить варианты для фильтров в проекте
    {
        $leads = $this->query()->from($project)->get();

        //Составление списка базовых и дополнительных полей
        $view_fields = collect($permissions->view_fields);
        $additional = $view_fields->reject(function($value){
            return in_array(needle: $value, haystack: [
                'utm_medium',
                'utm_source',
                'utm_campaign',
                'utm_content',
            ]);
        })->values();

        $fields = array_merge(UserPermissions::ALLOWED_BASIC_FIELDS, $additional->toArray());

        //Загрузка базовых полей
        foreach($fields as $field)
            $variants[$field] = $leads->whereNotNull($field)->pluck($field)->unique()->values();
        Arr::forget(array: $variants, keys: ['id']);

        //Загрузка UTM-меток
        $utm = $view_fields->map(function($value){
            if(in_array(needle: $value, haystack: [
                'utm_medium',
                'utm_source',
                'utm_campaign',
                'utm_content',
            ]))
                return $value;
        })->whereNotNull()->values();

        foreach($utm as $item)
            $variants[$item] = $leads->whereNotNull('utm')->pluck("utm->$field")->whereNotNull()->unique()->values();

        return $variants;
        
    } //getFilterVariants
};

?>