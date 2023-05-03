<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Chat\ProjectIndex;

use App\Http\Resources\V2\Project\Integrations\Telegram\Chat\ProjectIndexResource;

class ProjectIndexHandler
{
    /**
     * ProjectIndexHandler constructor.
     */
    public function __construct(
        private \App\Repositories\Project\Integrations\Telegram\Chat\Repository $chatRepository,
    )
    {
    }

    /**
     * @param ProjectIndexCommand $command
     */
    public function handle(ProjectIndexCommand $command)
    {
        $chats = $this->chatRepository->query()->from($command->request->project_id)->get();
        return ProjectIndexResource::collection($chats);
    }
}
