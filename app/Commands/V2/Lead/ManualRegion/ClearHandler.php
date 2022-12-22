<?php

namespace App\Commands\V2\Lead\ManualRegion;

use App\Repositories\Lead\Repository as LeadRepository;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use Illuminate\Http\Response;

class ClearHandler
{
    /**
     * ClearHandler constructor.
     */
    public function __construct(
        private LeadRepository $leadRepository,
        private LeadReadRepository $leadReadRepository,
    )
    {
    }

    /**
     * @param ClearCommand $command
     */
    public function handle(ClearCommand $command)
    {
        $lead = $this->leadReadRepository->findById(id: $command->request->lead_id, fail: true);

        $this->leadRepository->clearManualRegion(lead: $lead);

        return response(content: 'Регион удалён', status: Response::HTTP_NO_CONTENT);
    }
}
