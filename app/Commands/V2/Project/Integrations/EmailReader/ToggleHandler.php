<?php

namespace App\Commands\V2\Project\Integrations\EmailReader;

use App\Repositories\Project\Integrations\EmailReader\Repository;
use App\Repositories\Project\Integrations\EmailReader\ReadRepository;
use Illuminate\Http\Response;

class ToggleHandler
{
    /**
     * ToggleHandler constructor.
     */
    public function __construct(
        private Repository $repository,
        private ReadRepository $readRepository,
    )
    {
    }

    /**
     * @param ToggleCommand $command
     */
    public function handle(ToggleCommand $command)
    {
        $emailReader = $this->readRepository->findById(id: $command->request->reader_id, fail: true);

        $this->repository->update(
            emailReader: $emailReader,
            email: $emailReader->email,
            password: $emailReader->password,
            host: $emailReader->host,
            template: $emailReader->template,
            enabled: !$emailReader->enabled,
            interval: $emailReader->interval,
            mails_per_time: $emailReader->mails_per_time,
            mark_as_read: $emailReader->mark_as_read
        );

        return response(content: $emailReader->enabled ? 'Парсер включен' : 'Парсер отключен', status: Response::HTTP_OK);
    }
}
