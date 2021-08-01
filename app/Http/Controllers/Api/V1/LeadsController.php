<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LeadsRequest;
use App\Http\Resources\User;
use App\Models\Leads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LeadsController extends Controller
{
    public function store (LeadsRequest $request )
    {
        $lead = new Leads();
        $lead->project_id = $request->id;
        $lead->name = $request->name;
        $lead->phone = $request->phone;
        $lead->email = $request->email;
        $lead->cost = $request->cost;
        $lead->comment = $request->comment;
        $lead->city = $request->city;
        $lead->ip = $request->ip;
        $lead->referrer = $request->referrer;
        $lead->utm = $request->utm;

        $lead->save();


        return response()->json($request,201);
    }
}
