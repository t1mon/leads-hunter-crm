<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Chat\Create;

use Illuminate\Http\Response;

class CreateHandler
{
    /**
     * CreateHandler constructor.
     */
    public function __construct(
        private \App\Repositories\Project\Integrations\Telegram\Chat\Repository $chatRepository,
    )
    {
    }

    /**
     * @param CreateCommand $command
     */
    public function handle(CreateCommand $command)
    {
        $this->chatRepository->createBlank(
            project: $command->request->project_id,
            format: $command->request->format,
        );

        return response(content: 'Неподтверждённый чат создан', status: Response::HTTP_CREATED);
    }
}
