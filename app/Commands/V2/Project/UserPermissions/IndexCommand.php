<?php

namespace App\Commands\V2\Project\UserPermissions;

use App\Http\Requests\Api\V2\Project\UserPermissions\IndexRequest;

class IndexCommand
{
    /**
     * IndexCommand constructor.
     */
    public function __construct(
        public readonly IndexRequest $request,
    )
    {
    }
}
