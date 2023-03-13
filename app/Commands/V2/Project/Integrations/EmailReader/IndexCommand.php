<?php

namespace App\Commands\V2\Project\Integrations\EmailReader;

use App\Http\Requests\Api\V2\Project\Integrations\EmailReader\IndexRequest;

class IndexCommand
{
    /**
     * IndexCommand constructor.
     */
    public function __construct(
        public IndexRequest $request,
    )
    {
    }
}
