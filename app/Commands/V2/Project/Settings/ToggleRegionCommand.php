<?php

namespace App\Commands\V2\Project\Settings;

use App\Http\Requests\Api\V2\Project\Project\Settings\ToggleRegionRequest;

class ToggleRegionCommand
{
    /**
     * ToggleRegionCommand constructor.
     */
    public function __construct(
        public readonly ToggleRegionRequest $request
    )
    {
    }
}
