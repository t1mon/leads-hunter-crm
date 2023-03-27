<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Bot\Remove;

use App\Repositories\Project\Integrations\Telegram\Bot;
use Illuminate\Http\Response;

class RemoveHandler
{
    /**
     * RemoveHandler constructor.
     */
    public function __construct(
        private Bot\Repository $repository,
        private Bot\ReadRepository $readRepository,
    )
    {
    }

    /**
     * @param RemoveCommand $command
     */
    public function handle(RemoveCommand $command)
    {
        $bot = $this->readRepository->findById(id: $command->request->bot, fail: true);
        $this->repository->remove($bot);

        return response(content: 'Бот удалён', status: Response::HTTP_NO_CONTENT);
    }
}
