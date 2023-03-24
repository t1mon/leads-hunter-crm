<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Bot\Add;

use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Bot\AddRequest;

class AddCommand
{
    /**
     * AddCommand constructor.
     */
    public function __construct(
        public AddRequest $request,
    )
    {
    }
}
