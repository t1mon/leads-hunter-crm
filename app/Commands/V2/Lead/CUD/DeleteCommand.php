<?php

namespace App\Commands\V2\Lead\CUD;

use App\Http\Requests\Api\V2\Lead\Delete;

class DeleteCommand
{
    /**
     * DeleteCommand constructor.
     */
    public function __construct(
        public readonly Delete $request
    )
    {
    }
}
