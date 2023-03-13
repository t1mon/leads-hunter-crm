<?php

namespace App\Commands\V2\Project\Integrations\EmailReader;

use App\Http\Requests\Api\V2\Project\Integrations\EmailReader\ShowRequest;

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
