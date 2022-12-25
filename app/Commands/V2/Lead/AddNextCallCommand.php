<?php

namespace App\Commands\V2\Lead;

use App\Http\Requests\Api\V2\Lead\AddNextcallRequest;

class AddNextCallCommand
{
    public function __construct(
        public readonly AddNextcallRequest $request
    )
    {
        //
    }
}
