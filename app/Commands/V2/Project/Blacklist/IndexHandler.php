<?php

namespace App\Commands\V2\Project\Blacklist;

use App\Repositories\Project\Blacklist\ReadRepository as BlacklistReadRepository;
use App\Http\Resources\V2\Project\Blacklist\Index as BlacklistResource;

class IndexHandler
{
    /**
     * IndexHandler constructor.
     */
    public function __construct(
        private BlacklistReadRepository $blacklistReadRepository,
    )
    {
    }

    /**
     * @param IndexCommand $command
     */
    public function handle(IndexCommand $command)
    {
        $blacklists = $this->blacklistReadRepository->findByProject(project: $command->request->project_id);

        return BlacklistResource::collection($blacklists);
    }
}
