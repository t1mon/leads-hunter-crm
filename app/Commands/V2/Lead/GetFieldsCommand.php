<?php

namespace App\Commands\V2\Lead;

use App\Http\Requests\Api\V2\Lead\GetFieldsRequest;

class GetFieldsCommand
{
    /**
     * GetFieldsCommand constructor.
     */
    public function __construct(
        public readonly GetFieldsRequest $request,
    )
    {
    }
}
