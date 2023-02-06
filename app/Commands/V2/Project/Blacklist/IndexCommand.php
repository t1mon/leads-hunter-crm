<?php

namespace App\Commands\V2\Project\Blacklist;

use App\Http\Requests\Api\V2\Project\Blacklist\IndexRequest;

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
