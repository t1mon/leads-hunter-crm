<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LeadsRequest;
use App\Http\Resources\Leads as LeadsResource;
use App\Models\Host;
use App\Models\Leads;
use App\Models\Project;
use Illuminate\Http\Response;

use Illuminate\Support\Str;


class LeadsController extends Controller
{
    public function store(LeadsRequest $request)
    {
        if(filter_var($request->host, FILTER_VALIDATE_URL)){
            $host = parse_url($request->host);
            $request->merge([
                'host' => $host['host'],
            ]);
        }
        $request->merge(['host' =>  Str::lower($request->host)]);

        //Проверка хоста у лида
        $project = Project::findOrFail($request->project_id);

        if ($project->hasInHosts($request->host)) {
            return new LeadsResource(
                Leads::addToDB($request->all())
            );
        }

        return response()->json(['data' =>
                                        [
                                            'status'  => Host::HOST_NOT_FOUND,
                                            'message' => trans('leads.host-error'),
                                            'response' => Response::HTTP_PRECONDITION_FAILED,
                                        ]
                                    ],Response::HTTP_PRECONDITION_FAILED);
    }
}
