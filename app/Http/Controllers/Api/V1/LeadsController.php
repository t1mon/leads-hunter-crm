<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LeadsRequest;
use App\Models\Leads;
use App\Http\Resources\Leads as LeadsResource;
use App\Models\Project;
use Illuminate\Http\Response;

class LeadsController extends Controller
{
    public function store(LeadsRequest $request)
    {

//        if (Leads::where('project_id', $request->id)->where('phone', $request->phone)->count() != 0) {
//            return response()->json(['error' => 'phone already exists for this project'], Response::HTTP_UNPROCESSABLE_ENTITY);
//        }

//        $lead = new Leads();
//        $lead->project_id = $request->id;
//        $lead->name = $request->name;
//        $lead->phone = $request->phone;
//        $lead->email = $request->email;
//        $lead->cost = $request->cost;
//        $lead->comment = $request->comment;
//        $lead->city = $request->city;
//        $lead->ip = $request->ip;
//        $lead->referrer = $request->referrer;
//        $lead->utm = $request->utm;
//
//        $lead->save();
//
//
//        return response()->json(
//            ['data' => ['id' => $lead->id,'message' => 'Лид успешно добавлен']],
//            201,
//            ['Content-Type' => 'application/json;charset=utf8'],
//            JSON_UNESCAPED_UNICODE
//        );

        return new LeadsResource(
            Leads::create([
                'project_id' => $request->id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'cost' => $request->cost,
                'comment' => $request->comment,
                'city' => $request->city,
                'ip' => $request->ip,
                'referrer' => $request->referrer,
                'utm' => $request->utm
            ])
        );

    }
}
