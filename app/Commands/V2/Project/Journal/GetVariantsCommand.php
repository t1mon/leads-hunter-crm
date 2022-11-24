<?php

namespace App\Commands\V2\Project\Journal;

class GetVariantsCommand
{
    public function __construct(
        public int $project
    )
    {
    }
}
