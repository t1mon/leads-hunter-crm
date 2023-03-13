<?php

namespace App\Commands\V2\Project\Integrations\EmailReader;
use App\Repositories\Project\Integrations\EmailReader\Repository;
use App\Repositories\Project\Integrations\EmailReader\ReadRepository;
use Illuminate\Http\Response;

class UpdateHandler
{
    /**
     * UpdateHandler constructor.
     */
    public function __construct(
        private Repository $repository,
        private ReadRepository $readRepository,
    )
    {
    }

    /**
     * @param UpdateCommand $command
     */
    public function handle(UpdateCommand $command)
    {
        $emailReader = $this->readRepository->findById(id: $command->request->reader_id, fail: true);

        $this->repository->update(
            emailReader: $emailReader,
            email: $command->request->email,
            password: $command->request->password,
            host: $command->request->host,
            template: $command->request->template,
            enabled: $command->request->enabled,
            interval: $command->request->interval,
            mails_per_time: $command->request->mails_per_time,
            mark_as_read: $command->request->mark_as_read
        );

        return response(content: 'Парсер добавлен в проект', status: Response::HTTP_OK);
    }
}
