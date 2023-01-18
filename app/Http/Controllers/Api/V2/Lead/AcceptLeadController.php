<?php

namespace App\Http\Controllers\Api\V2\Lead;

use App\Commands\V2\Lead\Accept\AcceptCommand;
use App\Commands\V2\Lead\Accept\AcceptHandler;
use App\Commands\V2\Lead\Accept\DismissCommand;
use App\Commands\V2\Lead\Accept\DismissHandler;
use App\Commands\V2\Lead\Accept\GetUsersForProjectCommand;
use App\Commands\V2\Lead\Accept\GetUsersForProjectHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Lead\Accept\AssignRequest;
use App\Http\Requests\Api\V2\Lead\Accept\DismissRequest;
use App\Http\Requests\Api\V2\Lead\Accept\GetUsersRequest;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class AcceptLeadController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus
    )
    {
        //
    } //Конструктор

    public function assign(AssignRequest $request)
    {
        $this->bus->addHandler(command: AcceptCommand::class, handler: AcceptHandler::class);
        return $this->bus->dispatch(
            command: AcceptCommand::class,
            input: [
                'request' => $request,
            ],
        );
    } //store

    public function dismiss(DismissRequest $request)
    {
        $this->bus->addHandler(command: DismissCommand::class, handler: DismissHandler::class);
        return $this->bus->dispatch(
            command: DismissCommand::class,
            input: [
                'request' => $request,
            ],
        );
    } //destroy

    public function getUsersForProject(GetUsersRequest $request) //Получить список пользователей, которые могут принять лид в проекте
    {
        $this->bus->addHandler(command: GetUsersForProjectCommand::class, handler: GetUsersForProjectHandler::class);
        return $this->bus->dispatch(
            command: GetUsersForProjectCommand::class,
            input: [
                'request' => $request,
            ],
        );
    } //getUsersForProject
}
