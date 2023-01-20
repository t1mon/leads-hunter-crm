<?php

namespace App\Commands\V2\Project\UserPermissions;

use App\Http\Requests\Api\V2\Project\UserPermissions\ChangeRoleRequest;

class ChangeRoleCommand
{
    /**
     * ChangeRoleCommand constructor.
     */
    public function __construct(
        public readonly ChangeRoleRequest $request,
    )
    {
    }
}
