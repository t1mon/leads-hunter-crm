<?php

namespace App\Commands\V2\Project\Integrations\EmailReader;

use App\Http\Requests\Api\V2\Project\Integrations\EmailReader\ToggleRequest;

class ToggleCommand
{
    /**
     * ToggleCommand constructor.
     */
    public function __construct(
        public ToggleRequest $request,
    )
    {
    }
}
