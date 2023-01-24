<?php

namespace App\Commands\V2\Lead\CUD;

use App\Http\Requests\Api\V2\Lead\AddManually;

class AddManuallyCommand
{
    /**
     * AddManuallyCommand constructor.
     */
    public function __construct(
        public readonly AddManually $request)
    {
    }
}
