<?php

namespace App\Commands\V2\Lead\Accept;

use App\Http\Requests\Api\V2\Lead\Accept\AssignRequest;

class AcceptCommand
{
    public function __construct(
        public readonly AssignRequest $request,
    )
    {
    }
}
