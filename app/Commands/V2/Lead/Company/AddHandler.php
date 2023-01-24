<?php

namespace App\Commands\V2\Lead\Company;

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

        $this->leadRepository->update(lead: $lead, params: [
            'company' => $command->request->company,
        ]);

        return response(content: 'Компания добавлена в лид', status: Response::HTTP_CREATED);
    }
}
