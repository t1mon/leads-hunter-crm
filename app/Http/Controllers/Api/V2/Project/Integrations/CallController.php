<?php

namespace App\Http\Controllers\Api\V2\Project\Integrations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Project\Integrations\Calltracking\IncomingCall;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class CallController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus,
    )
    {
        //
    } //Конструктор

    public function __invoke(IncomingCall $request)
    {
        //Обязательный код от novaphone, без которого интеграция не сработает
        if (isset($_GET['zd_echo'])) exit($_GET['zd_echo']);

        return response('Данные получены');
    }
}
