<?php

namespace App\Commands\V2\Lead;

use App\Repositories\Lead\Repository as LeadRepository;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use Illuminate\Http\Response;

class ClearNextCallHandler
{
    /**
     * ClearNextCallHandler constructor.
     */
    public function __construct(
        private LeadRepository $leadRepository,
        private LeadReadRepository $leadReadRepository,
    )
    {
    }

    /**
     * @param ClearNextCallCommand $command
     */
    public function handle(ClearNextCallCommand $command)
    {
        //TODO После мержа заменить на findById из репозитория
        $lead = $this->leadReadRepository->query()->findOrFail($command->request->lead_id);

        $this->leadRepository->clearNextCallDate(lead: $lead);

        return response(content: 'Дата следующего звонка удалена', status: Response::HTTP_NO_CONTENT);
    }
}
