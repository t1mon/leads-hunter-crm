<?php

namespace App\Commands\V2\Lead;

use App\Http\Requests\Api\V2\Lead\ClearNextcallRequest;

class ClearNextCallCommand
{
    /**
     * ClearNextCallCommand constructor.
     */
    public function __construct(
        public readonly ClearNextcallRequest $request
    )
    {
    }
}
