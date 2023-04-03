<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Chat\Update;

use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Chat\UpdateRequest;

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
