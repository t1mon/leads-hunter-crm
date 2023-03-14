<?php

namespace App\Http\Controllers\Api\V2\Project\Integrations;

use App\Commands\V2\Project\Integrations\EmailReader\AddCommand;
use App\Commands\V2\Project\Integrations\EmailReader\AddHandler;
use App\Commands\V2\Project\Integrations\EmailReader\IndexCommand;
use App\Commands\V2\Project\Integrations\EmailReader\IndexHandler;
use App\Commands\V2\Project\Integrations\EmailReader\RemoveCommand;
use App\Commands\V2\Project\Integrations\EmailReader\RemoveHandler;
use App\Commands\V2\Project\Integrations\EmailReader\ShowCommand;
use App\Commands\V2\Project\Integrations\EmailReader\ShowHandler;
use App\Commands\V2\Project\Integrations\EmailReader\ToggleCommand;
use App\Commands\V2\Project\Integrations\EmailReader\ToggleHandler;
use App\Commands\V2\Project\Integrations\EmailReader\UpdateCommand;
use App\Commands\V2\Project\Integrations\EmailReader\UpdateHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Project\Integrations\EmailReader\AddRequest;
use App\Http\Requests\Api\V2\Project\Integrations\EmailReader\IndexRequest;
use App\Http\Requests\Api\V2\Project\Integrations\EmailReader\RemoveRequest;
use App\Http\Requests\Api\V2\Project\Integrations\EmailReader\ShowRequest;
use App\Http\Requests\Api\V2\Project\Integrations\EmailReader\ToggleRequest;
use App\Http\Requests\Api\V2\Project\Integrations\EmailReader\UpdateRequest;
use Illuminate\Http\Request;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class EmailReaderController extends Controller
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

    public function toggle(ToggleRequest $request)
    {
        $this->bus->addHandler(command: ToggleCommand::class, handler: ToggleHandler::class);
        return $this->bus->dispatch(command: ToggleRequest::class, input: ['request' => $request]);
    } //toggle

    public function destroy(RemoveRequest $request)
    {
        $this->bus->addHandler(command: RemoveCommand::class, handler: RemoveHandler::class);
        return $this->bus->dispatch(command: RemoveCommand::class, input: ['request' => $request]);
    } //destroy

    public function test(Request $request)
    {
        $request->validate([
            'reader_id' => 'required|exists:integrations_email_readers,id',
        ]);

        $repository = app(\App\Repositories\Project\Integrations\EmailReader\ReadRepository::class);
        $reader = $repository->query()->first();

        $reader->getMail();

        return response()->json(['success' => true,]);
    } //test
}
