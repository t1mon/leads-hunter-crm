<?php

namespace App\Commands\V2\Lead\ManualRegion;

use App\Http\Requests\Api\V2\Lead\ManualRegion\AddRequest;

class AddCommand
{
    /**
     * AddCommand constructor.
     */
    public function __construct(
        public readonly AddRequest $request
    )
    {
    }
}
