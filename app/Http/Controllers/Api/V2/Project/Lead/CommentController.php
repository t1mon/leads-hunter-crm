<?php

namespace App\Http\Controllers\Api\V2\Project\Lead;

use App\Commands\V2\Project\Lead\Comment\AddCommand;
use App\Commands\V2\Project\Lead\Comment\AddHandler;
use App\Commands\V2\Project\Lead\Comment\DeleteCommand;
use App\Commands\V2\Project\Lead\Comment\DeleteHandler;
use App\Commands\V2\Project\Lead\Comment\ShowCommand;
use App\Commands\V2\Project\Lead\Comment\ShowHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Project\Lead\Comment\AddRequest;
use App\Http\Requests\Api\V2\Project\Lead\Comment\DeleteRequest;
use App\Http\Requests\Api\V2\Project\Lead\Comment\ShowRequest;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class CommentController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus,
    )
    {} //Конструктор

    public function store(AddRequest $request)
    {
        $this->bus->addHandler(
            command: AddCommand::class,
            handler: AddHandler::class
        );

        return $this->bus->dispatch(
            command: AddCommand::class,
            input: ['request' => $request],
        );
    } //store
    
    public function show(ShowRequest $request)
    {
        $this->bus->addHandler(
            command: ShowCommand::class,
            handler: ShowHandler::class
        );

        return $this->bus->dispatch(
            command: ShowCommand::class,
            input: ['request' => $request]
        );
    } //show

    public function delete(DeleteRequest $request)
    {
        $this->bus->addHandler(
            command: DeleteCommand::class,
            handler: DeleteHandler::class
        );

        return $this->bus->dispatch(
            command: DeleteCommand::class,
            input: ['request' => $request]
        );
    } //delete
}
