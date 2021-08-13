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

//        if (Leads::where('project_id', $request->id)->where('phone', $request->phone)->count() != 0) {
//            return response()->json(['error' => 'phone already exists for this project'], Response::HTTP_UNPROCESSABLE_ENTITY);
//        }

//
//
//        return response()->json(
//            ['data' => ['id' => $lead->id,'message' => 'Лид успешно добавлен']],
//            201,
//            ['Content-Type' => 'application/json;charset=utf8'],
//            JSON_UNESCAPED_UNICODE
//        );
        Log::info('ip: '.$request->ip().' getHost:'.$request->getHost().' url:'.$request->url().' referer'.$request->headers->get('referer'));

        return new LeadsResource(
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
        );

    }
}
