<?php

namespace App\Commands\V2\Project\Integrations\EmailReader;

use App\Http\Requests\Api\V2\Project\Integrations\EmailReader\AddRequest;

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
