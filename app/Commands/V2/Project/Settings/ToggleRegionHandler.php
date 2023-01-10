<?php

namespace App\Commands\V2\Project\Settings;

use App\Repositories\Project;
use Illuminate\Http\Response;

class ToggleRegionHandler
{
    /**
     * ToggleRegionHandler constructor.
     */
    public function __construct(
        private Project\Repository $projectRepository,
        private Project\ReadRepository $projectReadRepository,
    )
    {
    }

    /**
     * @param ToggleRegionCommand $command
     */
    public function handle(ToggleRegionCommand $command)
    {
        $project = $this->projectReadRepository->findById($command->request->project_id);
        $project->find_region = $command->request->value;
        return response(content: 'Определение региона включено', status: Response::HTTP_OK);
    }
}
