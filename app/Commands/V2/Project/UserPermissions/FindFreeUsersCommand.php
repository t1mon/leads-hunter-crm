<?php

namespace App\Commands\V2\Project\UserPermissions;

use App\Http\Requests\Api\V2\Project\UserPermissions\FindFreeUsersRequest;

class FindFreeUsersCommand
{
    /**
     * FindFreeUsersCommand constructor.
     */
    public function __construct(
        public readonly FindFreeUsersRequest $request
    )
    {
    }
}
