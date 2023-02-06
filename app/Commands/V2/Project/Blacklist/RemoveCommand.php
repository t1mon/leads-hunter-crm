<?php

namespace App\Commands\V2\Project\Blacklist;

use App\Http\Requests\Api\V2\Project\Blacklist\RemoveRequest;

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
