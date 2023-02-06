<?php

namespace App\Commands\V2\Project\Blacklist;

use App\Repositories\Project\Blacklist\Repository as BlacklistRepository;
use Illuminate\Http\Response;

class AddHandler
{
    /**
     * AddHandler constructor.
     */
    public function __construct(
        private BlacklistRepository $blacklistRepository,
    )
    {
    }

    /**
     * @param AddCommand $command
     */
    public function handle(AddCommand $command)
    {
        $this->blacklistRepository->create(
            project: $command->request->project_id,
            phone: $command->request->phone,
            name: $command->request->name,
            comment: $command->request->comment,
        );

        return response('Номер добавлен в чёрный список', Response::HTTP_CREATED);
    }
}
