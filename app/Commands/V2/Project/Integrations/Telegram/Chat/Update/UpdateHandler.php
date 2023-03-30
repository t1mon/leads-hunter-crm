<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Chat\Update;

class UpdateHandler
{
    /**
     * UpdateHandler constructor.
     */
    public function __construct(
        private \App\Repositories\Project\Integrations\Telegram\Chat\Repository $chatRepository,
    )
    {
    }

    /**
     * @param UpdateCommand $command
     */
    public function handle(UpdateCommand $command)
    {
        $chat = $this->chatRepository->query()->findOrFail($command->request->chat);
        
        $this->chatRepository->update(
            chat: $chat,
            format: $command->request->format,
            enabled: $command->request->enabled ?? $chat->enabled
        );

        return response(content: 'Данные чата обновлены');
    }
}
