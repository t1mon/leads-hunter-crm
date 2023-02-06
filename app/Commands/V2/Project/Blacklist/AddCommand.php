<?php

namespace App\Commands\V2\Project\Blacklist;

use App\Http\Requests\Api\V2\Project\Blacklist\AddRequest;

class AddCommand
{
    /**
     * AddCommand constructor.
     */
    public function __construct(
        public AddRequest $request,
    )
    {
    }
}
