<?php

namespace App\Commands\V2\Project\Integrations\EmailReader;

use App\Http\Resources\V2\Project\Integrations\EmailReader\Index;
use App\Repositories\Project\Integrations\EmailReader\ReadRepository as EmailReaderRepository;
use Illuminate\Http\Response;

class IndexHandler
{
    /**
     * IndexHandler constructor.
     */
    public function __construct(
        private EmailReaderRepository $emailReaderRepository,
    )
    {
    }

    /**
     * @param IndexCommand $command
     */
    public function handle(IndexCommand $command)
    {
        $emaiLReaders = $this->emailReaderRepository->findByProject(project: $command->request->project_id, with: 'user');
        return Index::collection($emaiLReaders);
    }
}
