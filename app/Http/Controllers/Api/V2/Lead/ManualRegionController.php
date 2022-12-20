<?php

namespace App\Http\Controllers\Api\V2\Lead;

use App\Http\Controllers\Controller;
use Joselfonseca\LaravelTactician\CommandBusInterface;

use App\Http\Requests\Api\V2\Lead\ManualRegion\AddRequest;
use App\Http\Requests\Api\V2\Lead\ManualRegion\ClearRequest;

class ManualRegionController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus
    )
    {} //Конструктор

    public function store(AddRequest $request)
    {
        //
    } //store

    public function destroy(ClearRequest $request)
    {
        //
    } //destroy
}
