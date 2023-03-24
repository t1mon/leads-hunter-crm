<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Bot\Index;

use App\Http\Requests\Api\V2\Project\Integrations\Telegram\Bot\IndexRequest;

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
