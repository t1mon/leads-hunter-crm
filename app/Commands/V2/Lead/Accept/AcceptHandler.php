<?php

namespace App\Commands\V2\Lead\Accept;

use App\Repositories\Lead\Repository as LeadRepository;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use App\Repositories\User\ReadRepository as UserReadRepository;
use Illuminate\Http\Response;

class AcceptHandler
{
    /**
     * AcceptHandler constructor.
     */
    public function __construct(
        private LeadRepository $leadRepository,
        private LeadReadRepository $leadReadRepository,
        private UserReadRepository $userReadRepository,
    )
    {
    }

    /**
     * @param AcceptCommand $command
     */
    public function handle(AcceptCommand $command)
    {
        $lead = $this->leadReadRepository->findById(id: $command->request->lead_id, fail: true);
        $acceptor = $this->userReadRepository->findById(id: $command->request->acceptor_id, fail: true);
        $this->leadRepository->assignAcceptor(lead: $lead, user: $acceptor);

        return response(content: 'Пользователь принял лид', status: Response::HTTP_CREATED);
    }
}
