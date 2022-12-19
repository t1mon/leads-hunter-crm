<?php

namespace App\Http\Controllers\Api\V2;

use App\Commands\V2\Lead\AddNextCallCommand;
use App\Commands\V2\Lead\AddNextCallHandler;
use App\Commands\V2\Lead\ClearNextCallCommand;
use App\Commands\V2\Lead\ClearNextCallHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Lead\AddNextcallRequest;
use App\Http\Requests\Api\V2\Lead\ClearNextcallRequest;
use Illuminate\Http\Request;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class LeadController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus
    )
    {} //Конструктор

    public function addNextcall(AddNextcallRequest $request)
    {
        $this->bus->addHandler(command: AddNextCallCommand::class, handler: AddNextCallHandler::class);

        return $this->bus->dispatch(
            command: AddNextCallCommand::class,
            input: [
                'request' => $request,
            ],
        );
    } //addNextcall

    public function clearNextcall(ClearNextcallRequest $request)
    {
        $this->bus->addHandler(command: ClearNextCallCommand::class, handler: ClearNextCallHandler::class);

        return $this->bus->dispatch(
            command: ClearNextCallCommand::class,
            input: [
                'request' => $request,
            ],
        );
    } //clearNextcall
}
