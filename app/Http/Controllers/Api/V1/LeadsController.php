<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LeadsRequest;
use App\Models\Project;
use App\Models\Leads;
use App\Http\Resources\Leads as LeadsResource;

class LeadsController extends Controller
{
    public function store(LeadsRequest $request)
    {
        //Проверка хоста у лида
        $project = Project::findOrFail($request->project_id);
        if($project->hasInHosts($request->host))
            return new LeadsResource(
                Leads::addToDB($request->all())
            );
        else
            return response()->json(['data' =>
                                        [
                                            'message' => trans('leads.host-error'),
                                            'status' => 412,
                                        ]
                                    ]);
    }
}
