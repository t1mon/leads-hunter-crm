<?php

namespace App\Commands\V2\Project\Blacklist;

use App\Repositories\Project\Blacklist\Repository as BlacklistRepository;
use Illuminate\Http\Response;

class RemoveHandler
{
    /**
     * RemoveHandler constructor.
     */
    public function __construct(
        private BlacklistRepository $blacklistRepository,
    )
    {
    }

    /**
     * @param RemoveCommand $command
     */
    public function handle(RemoveCommand $command)
    {
        $this->blacklistRepository->remove($command->request->blacklist_id);

        return response(content: 'Запись удалена');
    }
}
