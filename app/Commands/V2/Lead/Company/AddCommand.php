<?php

namespace App\Commands\V2\Lead\Company;

use App\Http\Requests\Api\V2\Lead\Company\AddRequest;

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
