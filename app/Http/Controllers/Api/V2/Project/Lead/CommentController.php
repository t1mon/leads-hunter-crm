<?php

namespace App\Http\Controllers\Api\V2\Project\Lead;

use App\Commands\V2\Project\Lead\Comment\AddCommand;
use App\Commands\V2\Project\Lead\Comment\AddHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Project\Lead\Comment\AddRequest;
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
    
    public function show(int $project)
    {

    } //show

    public function delete(int $project)
    {

    } //delete
}
