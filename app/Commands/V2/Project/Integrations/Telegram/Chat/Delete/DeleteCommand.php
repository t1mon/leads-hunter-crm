<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Chat\Delete;

use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Chat\DeleteRequest;

class DeleteCommand
{
    /**
     * DeleteCommand constructor.
     */
    public function __construct(
        public DeleteRequest $request,
    )
    {
    }
}
