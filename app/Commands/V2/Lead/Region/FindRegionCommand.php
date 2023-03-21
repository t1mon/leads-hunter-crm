<?php

namespace App\Commands\V2\Lead\Region;

use App\Http\Requests\Api\V2\Lead\FindRegionRequest;

class FindRegionCommand
{
    /**
     * FindRegionCommand constructor.
     */
    public function __construct(
        public FindRegionRequest $request,
    )
    {
    }
}
