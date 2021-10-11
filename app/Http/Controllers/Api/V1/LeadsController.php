<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LeadsRequest;
use App\Models\Leads;
use App\Http\Resources\Leads as LeadsResource;
use App\Models\Project;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LeadsController extends Controller
{
    public function store(LeadsRequest $request)
    {
        Log::info($_SERVER);
        Log::info(request()->server());

        return new LeadsResource(
            Leads::addToDB($request->all())
            /*
            Leads::create([
                'project_id' => $request->project_id,
                'name' => $request->name,
                'surname' => $request->surname,
                'patronymic' => $request->patronymic,
                'phone' => $request->phone,
                'email' => $request->email,
                'cost' => $request->cost,
                'comment' => $request->comment,
                'city' => $request->city,
                'ip' => $request->ip,
                'referrer' => $request->referrer,
                'host' => $request->host,
                'url_query_string' => $request->url_query_string
            ])
            */
        );

    }
}
