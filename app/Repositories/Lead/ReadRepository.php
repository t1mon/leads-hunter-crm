<?php

namespace App\Repositories\Lead;

use App\Models\Leads;
use App\Models\Project\UserPermissions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReadRepository{
    private const PER_PAGE = 50;

    public function query(): Builder
    {
        return Leads::query();
    }

    public function filterFieldsPerPermissions(LengthAwarePaginator|Collection $leads, UserPermissions $permissions) //Отфильтровать поля лидов согласно правам пользователя
    {
        return $leads->map(function($item, $key){
            
        });
    } //filterFields
};

?>