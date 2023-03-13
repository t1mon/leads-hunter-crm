<?php

namespace App\Commands\V2\Project\Integrations\EmailReader;

use App\Http\Requests\Api\V2\Project\Integrations\EmailReader\RemoveRequest;

class RemoveCommand
{
    /**
     * RemoveCommand constructor.
     */
    public function __construct(
        public RemoveRequest $request,
    )
    {
    }
}
