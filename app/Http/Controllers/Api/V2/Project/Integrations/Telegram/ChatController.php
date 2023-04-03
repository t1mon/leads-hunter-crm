<?php

namespace App\Http\Controllers\Api\V2\Project\Integrations\Telegram;

use App\Commands\V2\Project\Integrations\Telegram\Chat\Create\CreateCommand;
use App\Commands\V2\Project\Integrations\Telegram\Chat\Create\CreateHandler;
use App\Commands\V2\Project\Integrations\Telegram\Chat\Delete\DeleteCommand;
use App\Commands\V2\Project\Integrations\Telegram\Chat\Delete\DeleteHandler;
use App\Commands\V2\Project\Integrations\Telegram\Chat\ProjectIndex\ProjectIndexCommand;
use App\Commands\V2\Project\Integrations\Telegram\Chat\ProjectIndex\ProjectIndexHandler;
use App\Commands\V2\Project\Integrations\Telegram\Chat\Show\ShowCommand;
use App\Commands\V2\Project\Integrations\Telegram\Chat\Show\ShowHandler;
use App\Commands\V2\Project\Integrations\Telegram\Chat\Update\UpdateCommand;
use App\Commands\V2\Project\Integrations\Telegram\Chat\Update\UpdateHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Chat\CreateRequest;
use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Chat\DeleteRequest;
use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Chat\ProjectIndexRequest;
use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Chat\ShowRequest;
use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Chat\UpdateRequest;
use Illuminate\Http\Request;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class ChatController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus,
    )
    {
        //
    } //Конструктор

    public function index(ProjectIndexRequest $request){
        $this->bus->addHandler(command: ProjectIndexCommand::class, handler: ProjectIndexHandler::class);
        return $this->bus->dispatch(command: ProjectIndexCommand::class, input: ['request' => $request]);
    } //

    public function show(ShowRequest $request){
        $this->bus->addHandler(command: ShowCommand::class, handler: ShowHandler::class);
        return $this->bus->dispatch(command: ShowCommand::class, input: ['request' => $request]);
    } //

    public function store(CreateRequest $request){
        $this->bus->addHandler(command: CreateCommand::class, handler: CreateHandler::class);
        return $this->bus->dispatch(command: CreateCommand::class, input: ['request' => $request]);
    } //

    public function update(UpdateRequest $request){
        $this->bus->addHandler(command: UpdateCommand::class, handler: UpdateHandler::class);
        return $this->bus->dispatch(command: UpdateCommand::class, input: ['request' => $request]);
    } //

    public function destroy(DeleteRequest $request){
        $this->bus->addHandler(command: DeleteCommand::class, handler: DeleteHandler::class);
        return $this->bus->dispatch(command: DeleteCommand::class, input: ['request' => $request]);
    } //
}
