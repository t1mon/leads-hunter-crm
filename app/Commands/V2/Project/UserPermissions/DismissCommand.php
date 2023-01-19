<?php

namespace App\Commands\V2\Project\UserPermissions;

use App\Http\Requests\Api\V2\Project\UserPermissions\DismissRequest;

class DismissCommand
{
    /**
     * DismissCommand constructor.
     */
    public function __construct(
        public readonly DismissRequest $request
    )
    {
    }
}
