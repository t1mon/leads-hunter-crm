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
        $emailReader = $this->readRepository->findById(id: $command->request->email_reader, fail: true);

        $this->repository->update(
            emailReader: $emailReader,
            subject: $command->request->subject,
            email: $command->request->email,
            password: $command->request->password,
            host: $command->request->host,
            template: $command->request->template,
            enabled: $command->request->enabled,
            interval: $command->request->interval,
            mails_per_time: $command->request->mails_per_time
        );

        return response(content: 'Данные парсера обновлены', status: Response::HTTP_OK);
    }
}
