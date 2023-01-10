<?php

namespace App\Http\Controllers\Api\V2\Project;

use App\Commands\V2\Project\Settings\ToggleRegionCommand;
use App\Commands\V2\Project\Settings\ToggleRegionHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Project\Project\Settings\ToggleRegionRequest;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class ProjectSettingsController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus
    )
    {
        //
    } //Конструктор

    public function toggleFindRegion(ToggleRegionRequest $request) //Настройка "Определять регион при добавлении"
    {
        $this->bus->addHandler(command: ToggleRegionCommand::class, handler: ToggleRegionHandler::class);
        return $this->bus->dispatch(
            command: ToggleRegionCommand::class,
            input: ['request' => $request]
        );
    } //toggleFindRegion
}
