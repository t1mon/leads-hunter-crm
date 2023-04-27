<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Chat\Show;

use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Chat\ShowRequest;

class ShowCommand
{
    /**
     * ShowCommand constructor.
     */
    public function __construct(
        public ShowRequest $request,
    )
    {
    }
}