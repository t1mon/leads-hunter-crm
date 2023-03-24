<?php

namespace App\Http\Controllers\Api\V2\Project\Integrations\Telegram;

use App\Commands\V2\Project\Integrations\Telegram\Bot\Add\AddCommand;
use App\Commands\V2\Project\Integrations\Telegram\Bot\Add\AddHandler;
use App\Commands\V2\Project\Integrations\Telegram\Bot\Index\IndexCommand;
use App\Commands\V2\Project\Integrations\Telegram\Bot\Index\IndexHandler;
use App\Commands\V2\Project\Integrations\Telegram\Bot\Remove\RemoveCommand;
use App\Commands\V2\Project\Integrations\Telegram\Bot\Remove\RemoveHandler;
use App\Commands\V2\Project\Integrations\Telegram\Bot\Show\ShowCommand;
use App\Commands\V2\Project\Integrations\Telegram\Bot\Show\ShowHandler;
use App\Commands\V2\Project\Integrations\Telegram\Bot\Update\UpdateCommand;
use App\Commands\V2\Project\Integrations\Telegram\Bot\Update\UpdateHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Bot\AddRequest;
use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Bot\IndexRequest;
use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Bot\RemoveRequest;
use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Bot\ShowRequest;
use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Bot\UpdateRequest;
use Illuminate\Http\Request;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class BotController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus,
    )
    {
        //
    }   // Конструктор

    public function index(IndexRequest $request)
    {
        $this->bus->addHandler(command: IndexCommand::class, handler: IndexHandler::class);
        return $this->bus->dispatch(command: IndexCommand::class, input: ['request' => $request]);
    } //index

    public function show(ShowRequest $request)
    {
        $this->bus->addHandler(command: ShowCommand::class, handler: ShowHandler::class);
        return $this->bus->dispatch(command: ShowCommand::class, input: ['request' => $request]);
    } //show

    public function store(AddRequest $request)
    {
        $this->bus->addHandler(command: AddCommand::class, handler: AddHandler::class);
        return $this->bus->dispatch(command: AddCommand::class, input: ['request' => $request]);
    } //store

    public function update(UpdateRequest $request)
    {
        $this->bus->addHandler(command: UpdateCommand::class, handler: UpdateHandler::class);
        return $this->bus->dispatch(command: UpdateCommand::class, input: ['request' => $request]);
    } //update

    public function destroy(RemoveRequest $request)
    {
        $this->bus->addHandler(command: RemoveCommand::class, handler: RemoveHandler::class);
        return $this->bus->dispatch(command: RemoveCommand::class, input: ['request' => $request]);
    } //destroy

    public function toggle()
    {

    } //destroy
}
