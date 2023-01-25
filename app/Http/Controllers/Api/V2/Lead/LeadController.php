<?php

namespace App\Http\Controllers\Api\V2\Lead;

use App\Commands\V2\Lead\CUD\AddManuallyCommand;
use App\Commands\V2\Lead\CUD\AddManuallyHandler;
use App\Commands\V2\Lead\CUD\DeleteCommand;
use App\Commands\V2\Lead\CUD\DeleteHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Lead\AddManually;
use App\Http\Requests\Api\V2\Lead\Delete;
use Joselfonseca\LaravelTactician\CommandBusInterface;

use App\Jobs\Api\V2\Lead\Add as LeadAddJob;

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

    public function storeApi(\App\Http\Requests\Api\LeadsRequest $request) //Добавить лид через API
    {
        LeadAddJob::dispatch($request);
        return response(content: 'Лид добавлен в очередь на обработку', status: 200);
    } //storeApi

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
}
