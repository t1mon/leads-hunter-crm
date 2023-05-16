<?php

namespace App\Http\Controllers\Api\V2\Project\Integrations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\Project\Integrations\Calltracking\IncomingCall;
use App\Jobs\Api\V2\Project\Integrations\Calltacking\ParseIncomingCall;
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
        dispatch(new ParseIncomingCall($request->result));

        return response('Данные получены');
    }
}
