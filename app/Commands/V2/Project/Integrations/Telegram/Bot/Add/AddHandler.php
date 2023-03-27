<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Bot\Add;

use App\Repositories\Project\Integrations\Telegram\Bot;
use Illuminate\Http\Response;

class AddHandler
{
    /**
     * AddHandler constructor.
     */
    public function __construct(
        private Bot\Repository $botRepository,
    )
    {
        //...
    }

    /**
     * @param AddCommand $command
     */
    public function handle(AddCommand $command)
    {
        $bot = $this->botRepository->create(
            project: $command->request->project_id,
            username: $command->request->username,
            api_token: $command->request->bot_api_token
        );

        //Привязка к вебхуку
        $bot->setWebhook();

        //Привязка стандартных команд
        $bot->setDefaultCommands();

        return response(content: 'Бот добавлен', status: Response::HTTP_CREATED);
    }
}
