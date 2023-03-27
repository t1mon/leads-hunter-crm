<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Bot\Update;

use App\Repositories\Project\Integrations\Telegram\Bot;

class UpdateHandler
{
    /**
     * UpdateHandler constructor.
     */
    public function __construct(
        private Bot\Repository $repository,
        private Bot\ReadRepository $readRepository,
    )
    {
    }

    /**
     * @param UpdateCommand $command
     */
    public function handle(UpdateCommand $command)
    {
        $bot = $this->readRepository->findById(id: $command->request->bot, fail: true);
        
        $this->repository->update(
            bot: $bot,
            username: $command->request->username,
            api_token: $command->request->bot_api_token,
            enabled: $command->request->enabled ?? $bot->enabled,
        );

        return response(content: 'Данные бота обновлены');
    }
}
