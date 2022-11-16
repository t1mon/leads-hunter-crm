<?php

namespace App\Repositories\Lead;

use App\Models\Leads;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Builder;

class ReadRepository{
    public function query(): Builder
    {
        return Leads::query();
    }
};

?>