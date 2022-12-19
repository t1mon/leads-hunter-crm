<?php

namespace App\Commands\V2\Lead;

use App\Repositories\Lead\Repository as LeadRepository;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use Illuminate\Http\Response;

class AddNextCallHandler
{
    /**
     * AddNextCallHandler constructor.
     */
    public function __construct(
        private LeadRepository $leadRepository,
        private LeadReadRepository $leadReadRepository,
    )
    {}

    /**
     * @param AddNextCallCommand $command
     */
    public function handle(AddNextCallCommand $command)
    {
        //TODO После мержа заменить на findById из репозитория
        $lead = $this->leadReadRepository->query()->findOrFail($command->request->lead_id);

        $this->leadRepository->addNextCallDate(lead: $lead, datetime: $command->request->datetime);

        return response(content: 'Дата следующего звонка добавлена', status: Response::HTTP_OK);
    }
}
