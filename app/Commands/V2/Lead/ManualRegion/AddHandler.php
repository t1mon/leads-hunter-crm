<?php

namespace App\Commands\V2\Lead\ManualRegion;

use App\Repositories\Lead\Repository as LeadRepository;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use Illuminate\Http\Response;

class AddHandler
{
    /**
     * AddHandler constructor.
     */
    public function __construct(
        private LeadRepository $leadRepository,
        private LeadReadRepository $leadReadRepository,
    )
    {
    }

    /**
     * @param AddCommand $command
     */
    public function handle(AddCommand $command)
    {
        $lead = $this->leadReadRepository->findById(id: $command->request->lead_id, fail: true);

        $this->leadRepository->addManualRegion(lead: $lead, region: $command->request->region);

        return response(content: 'Регион добавлен', status: Response::HTTP_CREATED);
    }
}
