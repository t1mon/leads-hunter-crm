<?php

namespace App\Commands\V2\Project\Integrations\EmailReader;

use App\Repositories\Project\Integrations\EmailReader\Repository;
use App\Repositories\Project\Integrations\EmailReader\ReadRepository;
use Illuminate\Http\Response;

class RemoveHandler
{
    /**
     * RemoveHandler constructor.
     */
    public function __construct(
        private Repository $repository,
        private ReadRepository $readRepository,
    )
    {
    }

    /**
     * @param RemoveCommand $command
     */
    public function handle(RemoveCommand $command)
    {
        $emailReader = $this->readRepository->findById(id: $command->request->email_reader, fail: true);
        $this->repository->remove($emailReader);

        return response(content: 'Парсер удалён из проекта', status: Response::HTTP_NO_CONTENT);
    }
}
