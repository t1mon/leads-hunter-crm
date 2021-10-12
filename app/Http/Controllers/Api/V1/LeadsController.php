<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LeadsRequest;
use App\Models\Leads;
use App\Http\Resources\Leads as LeadsResource;

class LeadsController extends Controller
{
    public function store(LeadsRequest $request)
    {
        return new LeadsResource(
            Leads::addToDB($request->all())
        );

    }
}
