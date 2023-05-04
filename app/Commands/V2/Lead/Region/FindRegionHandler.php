<?php

namespace App\Commands\V2\Lead\Region;

use App\Repositories\Lead\Repository as LeadRepository;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use Illuminate\Http\Response;

class FindRegionHandler
{
    /**
     * FindRegionHandler constructor.
     */
    public function __construct(
        private LeadRepository $leadRepository,
        private LeadReadRepository $leadReadRepository,
    )
    {
    }

    /**
     * @param FindRegionCommand $command
     */
    public function handle(FindRegionCommand $command)
    {
        $lead = $this->leadReadRepository->findById(id: $command->request->lead_id, fail: true);
        $code = $this->leadRepository->findRegion(lead: $lead);

        if($code == LeadRepository::STATUS_OK)
            return response(content: 'Регион определён успешно', status: Response::HTTP_OK);
        else
            return response(content: 'Ошибка запроса', status: Response::HTTP_NOT_FOUND);
    }
}
