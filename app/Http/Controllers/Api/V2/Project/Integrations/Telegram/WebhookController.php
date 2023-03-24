<?php

namespace App\Http\Controllers\Api\V2\Project\Integrations\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class WebhookController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus
    )
    {
        //
    } //Конструктор

    public function getIncomingRequest() //Обработать входящие запросы от Telegram
    {
        return 200;
    } //getIncomingRequest

    public function setWebhook(Request $request)
    {
        $request->validate(rules: [
            'bot_id' => 'required|exists:integrations_tg_bots,id',
        ]);
        
        $repository = app(\App\Repositories\Project\Integrations\Telegram\Bot\Repository::class);
        $readRepository = app(\App\Repositories\Project\Integrations\Telegram\Bot\ReadRepository::class);

        $bot = $readRepository->findById(id: $request->bot_id, fail: true);

        if($bot->checkWebhook())
            $bot->deleteWebhook();

        $repository->setWebhook($bot);

        return response(content: 'Вебхук назначен');
    } //setWebhook

    public function deleteWebhook()
    {

    } //deleteWebhook

    public function getWebhookInfo()
    {

    } //getWebhookInfo
}
