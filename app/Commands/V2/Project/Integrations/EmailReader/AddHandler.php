<?php

namespace App\Commands\V2\Project\Integrations\EmailReader;

use App\Repositories\Project\Integrations\EmailReader\Repository as EmailReaderRepository;
use Illuminate\Http\Response;

class AddHandler
{
    /**
     * AddHandler constructor.
     */
    public function __construct(
        private EmailReaderRepository $emailReaderRepository,
    )
    {
    }

    /**
     * @param AddCommand $command
     */
    public function handle(AddCommand $command)
    {
        $this->emailReaderRepository->create(
            user: $command->request->user(),
            project: $command->request->project_id,
            email: $command->request->email,
            password: $command->request->password,
            subject: $command->request->subject,
            host: $command->request->host,
            template: $command->request->template,
            enabled: $command->request->enabled,
            interval: $command->request->interval,
            mails_per_time: $command->request->mails_per_time,
        );

        return response(content: 'Парсер добавлен в проект', status: Response::HTTP_CREATED);
    }
}
