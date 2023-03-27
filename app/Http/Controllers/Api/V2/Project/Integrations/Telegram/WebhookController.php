<?php

namespace App\Http\Controllers\Api\V2\Project\Integrations\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Joselfonseca\LaravelTactician\CommandBusInterface;
use TeleBot;
use WeStacks\TeleBot\Objects\Update;

class WebhookController extends Controller
{
    public function __construct(
        private CommandBusInterface $bus
    )
    {
        //
    } //Конструктор

    public function getIncomingRequest(Request $request) //Обработать входящие запросы от Telegram
    {
        try{
            if(!env(key: 'TELEGRAM_ENABLED', default: false))
                return 200;
            
            //Загрузка бота и обработка обновления
            $bot = TeleBot::bot('bot');
            $update = Update::create($request->all());

            //Валидация сообщения
            //Если нет тела message, ничего не делать
            if(is_null($update->message()))
                throw new \Exception(message: 'Сообщения нет');

            $message = $update->message();

            //Не реагировать, если сообщение отправлено от какого-то другого бота
            if($update->message->from->is_bot)
                throw new \Exception(message: 'Сообщение отправлено ботом');

            //В групповых чатах и каналах проверять упоминание
            if($message->chat->type !== 'private')
            {
                $messageEntity = false;

                if(preg_match(pattern: '/^@' . env('TELEGRAM_BOT_NAME') . ' \w{6}$/', subject: $message->text)){
                    $messageEntity = true;
                }

                if(preg_match(pattern: '/^\/(start|stop)' . ('@'.env('TELEGRAM_BOT_NAME')) . '$/', subject: $message->text)){
                    $messageEntity = true;
                }

            
                $bot->sendMessage([
                    'chat_id' => $update->message()->chat->id,
                    'text' => $messageEntity ? 'Понял принял' : 'я Ничоег ни понел!!!',
                ]);
            }
        }
        catch(\Throwable $e){
            return 200;
        }

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
