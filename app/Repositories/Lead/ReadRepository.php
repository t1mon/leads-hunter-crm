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

    public function getFilterVariants(Project|int $project, UserPermissions $permissions) //Получить варианты для фильтров в проекте
    {
        //Загрузка базовых значений
        $basic = $this->query()
            ->from($project)
            ->select(UserPermissions::ALLOWED_BASIC_FIELDS)
            ->get()
            ->all();

        //TODO Загрузка вариантов utm-меток


        //Загрузка дополнительных полей
        return $basic;
        
    } //getFilterVariants
};

?>