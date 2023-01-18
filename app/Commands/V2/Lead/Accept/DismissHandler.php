<?php

namespace App\Commands\V2\Lead\Accept;

use App\Repositories\Lead\Repository as LeadRepository;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use Illuminate\Http\Response;

class DismissHandler
{
    /**
     * DismissHandler constructor.
     */
    public function __construct(
        private LeadRepository $leadRepository,
        private LeadReadRepository $leadReadRepository,
    )
    {
    }

    /**
     * @param DismissCommand $command
     */
    public function handle(DismissCommand $command)
    {
        $lead = $this->leadReadRepository->findById(id: $command->request->lead_it, fail: true);
        $this->leadRepository->dismissAcceptor(lead: $lead);

        return response(content: 'Пользователь убран с лида', status: Response::HTTP_NO_CONTENT);
    }
}
