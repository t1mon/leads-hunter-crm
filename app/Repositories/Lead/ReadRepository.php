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
    
    public function query(): Builder
    {
        return Leads::query();
    }
};

?>