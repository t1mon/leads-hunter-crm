<?php

namespace App\Commands\V2\Lead\CUD;
use App\Repositories\Lead\Repository as LeadRepository;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use Illuminate\Http\Response;

class DeleteHandler
{
    /**
     * DeleteHandler constructor.
     */
    public function __construct(
        private LeadRepository $leadRepository,
        private LeadReadRepository $leadReadRepository,
    )
    {
    }

    /**
     * @param DeleteCommand $command
     */
    public function handle(DeleteCommand $command)
    {
        $lead = $this->leadReadRepository->findById(id: $command->request->lead_id, fail: true);
        $this->leadRepository->remove($lead);

        return response(content: 'Лид удалён', status: Response::HTTP_NO_CONTENT);
    }
}
