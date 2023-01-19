<?php

namespace App\Commands\V2\Project\UserPermissions;

use App\Http\Requests\Api\V2\Project\UserPermissions\AssignRequest;

class AssignCommand
{
    /**
     * AssignCommand constructor.
     */
    public function __construct(
        public readonly AssignRequest $request,
    )
    {
    }
}
