<?php

namespace App\Commands\V2\Lead\Accept;

use App\Http\Requests\Api\V2\Lead\Accept\DismissRequest;

class DismissCommand
{
    /**
     * DismissCommand constructor.
     */
    public function __construct(
        public readonly DismissRequest $request,
    )
    {
    }
}
