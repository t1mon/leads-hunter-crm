<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Chat\Delete;

use Illuminate\Http\Response;

class DeleteHandler
{
    /**
     * DeleteHandler constructor.
     */
    public function __construct(
        private \App\Repositories\Project\Integrations\Telegram\Chat\Repository $chatRepository,
    )
    {
    }

    /**
     * @param DeleteCommand $command
     */
    public function handle(DeleteCommand $command)
    {
        $chat = $this->chatRepository->query()->findOrFail($command->request->chat);

        $this->chatRepository->remove($chat);

        return response(content: 'Чат удалён', status: Response::HTTP_NO_CONTENT);
    }
}
