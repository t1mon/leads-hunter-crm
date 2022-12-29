<?php

namespace App\Commands\V2\Lead\CUD;

use App\Repositories\Lead\Repository as LeadRepository;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use Illuminate\Http\Response;

class AddManuallyHandler
{
    /**
     * AddManuallyHandler constructor.
     */
    public function __construct(
        private LeadRepository $leadRepository,
        private ProjectReadRepository $projectReadRepository,
    )
    {
    }

    /**
     * @param AddManuallyCommand $command
     */
    public function handle(AddManuallyCommand $command)
    {
        $project = $this->projectReadRepository->findById(id: $command->request->project_id, fail: true);

        $this->leadRepository->create(
            project: $project,
            name: $command->request->name,
            phone: $command->request->phone,
            host: $command->request->host,
            surname: $command->request->surname,
            patronymic: $command->request->patronymic,
            owner: $command->request->owner,
            cost: $command->request->cost,
            email: $command->request->email,
            comment: $command->request->comment,
            city: $command->request->city,
            region: null, //TODO решить вопрос автоматического выставления региона
            manual_region: $command->request->manual_region,
            company: $command->request->company,
            ip: $command->request->ip,
            referrer: $command->request->referrer,
            source: $command->request->source,
            utm_medium: $command->request->utm_medium,
            utm_campaign: $command->request->utm_campaign,
            utm_source: $command->request->utm_source,
            utm_term: $command->request->utm_term,
            utm_content: $command->request->utm_content,
            url_query_string: $command->request->url_query_string,
            nextcall_date: $command->request->nextcall_date,
        );

        return response(content: 'Лид добавлен в проект', status: Response::HTTP_CREATED);
    }
}
