<?php

namespace App\Http\Controllers\Api\V2\Project;

use App\Commands\V2\Project\UserPermissions\AssignCommand;
use App\Commands\V2\Project\UserPermissions\AssignHandler;
use App\Commands\V2\Project\UserPermissions\ChangeRoleCommand;
use App\Commands\V2\Project\UserPermissions\ChangeRoleHandler;
use App\Commands\V2\Project\UserPermissions\DismissCommand;
use App\Commands\V2\Project\UserPermissions\DismissHandler;
use App\Commands\V2\Project\UserPermissions\IndexCommand;
use App\Commands\V2\Project\UserPermissions\IndexHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Project\UserPermissions\AssignRequest;
use App\Http\Requests\Api\V2\Project\UserPermissions\ChangeRoleRequest;
use App\Http\Requests\Api\V2\Project\UserPermissions\DismissRequest;
use App\Http\Requests\Api\V2\Project\UserPermissions\IndexRequest;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class UserPermissionsController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus,
    )
    {
        //
    } //Конструктор

    private function execute(string $command, string $handler, $request) //Выполнить команду (чтобы не повторять один и тот же код)
    {
        $this->bus->addHandler(command: $command, handler: $handler);
        return $this->bus->dispatch(
            command: $command,
            input: [
                'request' => $request,
            ]
        );
    } //execute

    public function index(IndexRequest $request) //Показать пользователей, назначенных на проект
    {
        return $this->execute(command: IndexCommand::class, handler: IndexHandler::class, request: $request);
    } //index

    public function assign(AssignRequest $request) //Назначить на проект
    {
        return $this->execute(command: AssignCommand::class, handler: AssignHandler::class, request: $request);
    } //assign

    public function changeRole(ChangeRoleRequest $request) //Изменить полномочия в проекте
    {
        return $this->execute(command: ChangeRoleCommand::class, handler: ChangeRoleHandler::class, request: $request);
    } //changeRole

    public function dismiss(DismissRequest $request) //Убрать из проекта
    {
        return $this->execute(command: DismissCommand::class, handler: DismissHandler::class, request: $request);
    } //dismiss
}
