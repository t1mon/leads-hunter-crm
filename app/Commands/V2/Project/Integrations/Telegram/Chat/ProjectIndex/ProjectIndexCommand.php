<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Chat\ProjectIndex;

use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Chat\ProjectIndexRequest;

class ProjectIndexCommand
{
    /**
     * ProjectIndexCommand constructor.
     */
    public function __construct(
        public ProjectIndexRequest $request,
    )
    {
    }
}
