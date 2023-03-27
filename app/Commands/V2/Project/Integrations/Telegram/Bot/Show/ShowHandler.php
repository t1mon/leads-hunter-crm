<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Bot\Show;

use App\Http\Resources\V2\Project\Integrations\Telegram\Bot\ShowResource;
use App\Repositories\Project\Integrations\Telegram\Bot;

class ShowHandler
{
    /**
     * ShowHandler constructor.
     */
    public function __construct(
        private Bot\ReadRepository $readRepository,
    )
    {
    }

    /**
     * @param ShowCommand $command
     */
    public function handle(ShowCommand $command)
    {
        $bot = $this->readRepository->findById(id: $command->request->bot, fail: true);

        return new ShowResource($bot);
    }
}
