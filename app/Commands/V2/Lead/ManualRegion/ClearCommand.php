<?php

namespace App\Commands\V2\Lead\ManualRegion;

use App\Http\Requests\Api\V2\Lead\ManualRegion\ClearRequest;

class ClearCommand
{
    /**
     * ClearCommand constructor.
     */
    public function __construct(
        public readonly ClearRequest $request
    )
    {
    }
}
