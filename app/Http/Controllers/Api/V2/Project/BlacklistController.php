<?php

namespace App\Http\Controllers\Api\V2\Project;

use App\Commands\V2\Project\Blacklist\AddCommand;
use App\Commands\V2\Project\Blacklist\AddHandler;
use App\Commands\V2\Project\Blacklist\IndexCommand;
use App\Commands\V2\Project\Blacklist\IndexHandler;
use App\Commands\V2\Project\Blacklist\RemoveCommand;
use App\Commands\V2\Project\Blacklist\RemoveHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Project\Blacklist\AddRequest;
use App\Http\Requests\Api\V2\Project\Blacklist\IndexRequest;
use App\Http\Requests\Api\V2\Project\Blacklist\RemoveRequest;
use Illuminate\Http\Request;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class BlacklistController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus,
    )
    {
        //
    } //Конструктор

    public function index(IndexRequest $request)
    {
        $this->bus->addHandler(command: IndexCommand::class, handler: IndexHandler::class);
        return $this->bus->dispatch(
            command: IndexCommand::class,
            input: ['request' => $request]
        );
    } //index

    public function store(AddRequest $request)
    {
        $this->bus->addHandler(command: AddCommand::class, handler: AddHandler::class);
        return $this->bus->dispatch(
            command: AddCommand::class,
            input: ['request' => $request]
        );
    } //store

    public function destroy(RemoveRequest $request)
    {
        $this->bus->addHandler(command: RemoveCommand::class, handler: RemoveHandler::class);
        return $this->bus->dispatch(
            command: RemoveCommand::class,
            input: ['request' => $request]
        );
    } //destroy

}
