<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LeadsRequest;
use App\Http\Resources\Leads as LeadsResource;
use App\Models\Project\Host;
use App\Models\Leads;
use App\Models\Project\Project;
use Illuminate\Http\Response;

use Illuminate\Support\Str;


class LeadsController extends Controller
{
    public function store(LeadsRequest $request)
    {
        if(filter_var($request->host, FILTER_VALIDATE_URL)){
            $host = parse_url($request->host);
            $request->merge(['host' => $host['host']]);
        }
        $request->merge(['host' =>  Str::lower($request->host)]);
        $phone = $request->phone;

        if ($phone[0] == 8) {
            $phone = preg_replace('/^./','7', $phone);
            $request->merge(['phone' => $phone]);
        }

        //Проверка хоста у лида
        if(Host::where([ ['host', $request->host], ['project_id', $request->project_id] ])->exists()){
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
