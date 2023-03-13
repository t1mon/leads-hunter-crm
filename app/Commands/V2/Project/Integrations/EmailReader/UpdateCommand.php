<?php

namespace App\Commands\V2\Project\Integrations\EmailReader;

use App\Http\Requests\Api\V2\Project\Integrations\EmailReader\UpdateRequest;

class UpdateCommand
{
    /**
     * UpdateCommand constructor.
     */
    public function __construct(
        public UpdateRequest $request,
    )
    {
    }
}
