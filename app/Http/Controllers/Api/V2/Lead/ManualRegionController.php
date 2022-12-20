<?php

namespace App\Http\Controllers\Api\V2\Lead;

use App\Http\Controllers\Controller;
use Joselfonseca\LaravelTactician\CommandBusInterface;

use App\Http\Requests\Api\V2\Lead\ManualRegion\AddRequest;
use App\Http\Requests\Api\V2\Lead\ManualRegion\ClearRequest;
use App\Commands\V2\Lead\ManualRegion\AddCommand;
use App\Commands\V2\Lead\ManualRegion\AddHandler;
use App\Commands\V2\Lead\ManualRegion\ClearCommand;
use App\Commands\V2\Lead\ManualRegion\ClearHandler;

class ManualRegionController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus
    )
    {} //Конструктор

    public function store(AddRequest $request)
    {
        $this->bus->addHandler(command: AddCommand::class, handler: AddHandler::class);

        return $this->bus->dispatch(
            command: AddCommand::class,
            input: [
                'request' => $request,
            ],
        );
    } //store

    public function destroy(ClearRequest $request)
    {
        $this->bus->addHandler(command: ClearCommand::class, handler: ClearHandler::class);

        return $this->bus->dispatch(
            command: ClearCommand::class,
            input: [
                'request' => $request,
            ],
        );
    } //destroy
}
