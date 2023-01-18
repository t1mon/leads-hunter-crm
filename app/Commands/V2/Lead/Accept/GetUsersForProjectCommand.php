<?php

namespace App\Commands\V2\Lead\Accept;

use App\Http\Requests\Api\V2\Lead\Accept\GetUsersRequest;

class GetUsersForProjectCommand
{
    /**
     * GetUsersForProjectCommand constructor.
     */
    public function __construct(
        public readonly GetUsersRequest $request,
    )
    {
    }
}
