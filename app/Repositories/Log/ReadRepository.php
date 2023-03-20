<?php

namespace App\Repositories\Log;

use App\Models\Project\Project;
use Illuminate\Support\Facades\DB;

class ReadRepository{
    private const TABLE = 'journal';
    private const PER_PAGE = 10;

    public function query()
    {
        //
    } //query

    public function findForProject(Project|int $project, int $perPage = self::PER_PAGE)
    {
        $entries = DB::table(self::TABLE)->where('data->project->id', $project->id)->orderByDesc('date')->paginate($perPage);

        return $entries->through(function($item){
            return json_decode($item->data);
        });
    } //findForProject
};

?>