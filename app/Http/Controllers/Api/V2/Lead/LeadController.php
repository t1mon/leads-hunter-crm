<?php

namespace App\Http\Controllers\Api\V2\Lead;

use App\Commands\V2\Lead\CUD\AddManuallyCommand;
use App\Commands\V2\Lead\CUD\AddManuallyHandler;
use App\Commands\V2\Lead\CUD\DeleteCommand;
use App\Commands\V2\Lead\CUD\DeleteHandler;
use App\Commands\V2\Lead\Region\FindRegionCommand;
use App\Commands\V2\Lead\Region\FindRegionHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Lead\AddManually;
use App\Http\Requests\Api\V2\Lead\Delete;
use App\Http\Requests\Api\V2\Lead\FindRegionRequest;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class LeadController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus
    )
    {} //Конструктор

    public function store(AddManually $request)
    {
        $this->bus->addHandler(command: AddManuallyCommand::class, handler: AddManuallyHandler::class);
        return $this->bus->dispatch(
            command: AddManuallyCommand::class,
            input: ['request' => $request]
        );
    } //store

    public function show()
    {
        //
    } //show

    public function update()
    {
        //
    } //update

    public function destroy(Delete $request)
    {
        $this->bus->addHandler(command: DeleteCommand::class, handler: DeleteHandler::class);
        return $this->bus->dispatch(
            command: DeleteCommand::class,
            input: ['request' => $request]
        );
    } //destroy

    public function findRegion(FindRegionRequest $request)
    {
        $this->bus->addHandler(command: FindRegionCommand::class, handler: FindRegionHandler::class);
        return $this->bus->dispatch(
            command: FindRegionCommand::class,
            input: ['request' => $request]
        );
    }
}
