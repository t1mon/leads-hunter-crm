<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Chat\Show;

use App\Http\Resources\V2\Project\Integrations\Telegram\Chat\ShowResource;

class ShowHandler
{
    /**
     * ShowHandler constructor.
     */
    public function __construct(
        private \App\Repositories\Project\Integrations\Telegram\Chat\Repository $chatRepository,
    )
    {
    }

    /**
     * @param ShowCommand $command
     */
    public function handle(ShowCommand $command)
    {
        $chat = $this->chatRepository->query()->findOrFail($command->request->chat);
        return new ShowResource($chat);
    }
}
