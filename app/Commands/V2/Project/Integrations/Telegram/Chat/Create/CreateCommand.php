<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Chat\Create;

use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Chat\CreateRequest;

class CreateCommand
{
    /**
     * CreateCommand constructor.
     */
    public function __construct(
        public CreateRequest $request,
    )
    {
    }
}
