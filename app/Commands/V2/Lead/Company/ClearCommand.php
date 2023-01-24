<?php

namespace App\Commands\V2\Lead\Company;

use App\Http\Requests\Api\V2\Lead\Company\ClearRequest;

class ClearCommand
{
    /**
     * ClearCommand constructor.
     */
    public function __construct(
        public readonly ClearRequest $request,
    )
    {
    }
}
