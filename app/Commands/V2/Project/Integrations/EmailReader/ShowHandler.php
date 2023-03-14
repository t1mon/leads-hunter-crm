<?php

namespace App\Commands\V2\Project\Integrations\EmailReader;

use App\Http\Resources\V2\Project\Integrations\EmailReader\Show;
use App\Repositories\Project\Integrations\EmailReader\ReadRepository as EmailReadRepository;

class ShowHandler
{
    /**
     * ShowHandler constructor.
     */
    public function __construct(
        private EmailReadRepository $emailReadRepository,
    )
    {
    }

    /**
     * @param ShowCommand $command
     */
    public function handle(ShowCommand $command)
    {
        $emailReader = $this->emailReadRepository->findById(id: $command->request->email_reader, fail: true, with: ['user', 'project']);
        return new Show($emailReader);
    }
}
