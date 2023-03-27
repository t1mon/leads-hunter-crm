<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Bot\Index;

use App\Http\Resources\V2\Project\Integrations\Telegram\Bot\IndexResource;
use App\Repositories\Project\Integrations\Telegram\Bot;

class IndexHandler
{
    /**
     * IndexHandler constructor.
     */
    public function __construct(
        private Bot\ReadRepository $botReadRepository,
    )
    {
    }

    /**
     * @param IndexCommand $command
     */
    public function handle(IndexCommand $command)
    {
        $bots = $this->botReadRepository->findByProject($command->request->project_id);
        return IndexResource::collection($bots);
    }
}
