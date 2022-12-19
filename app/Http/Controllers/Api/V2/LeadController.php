<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Lead\AddNextcallRequest;
use App\Http\Requests\Api\V2\Lead\ClearNextcallRequest;
use Illuminate\Http\Request;
use League\Tactician\CommandBus;

class LeadController extends Controller
{
    public function __construct(
        private CommandBus $bus
    )
    {} //Конструктор

    public function addNextcall(AddNextcallRequest $request)
    {

    } //addNextcall

    public function clearNextcall(ClearNextcallRequest $request)
    {

    } //clearNextcall
}
