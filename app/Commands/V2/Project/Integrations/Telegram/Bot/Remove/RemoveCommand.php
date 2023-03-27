<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Bot\Remove;

use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Bot\RemoveRequest;

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
