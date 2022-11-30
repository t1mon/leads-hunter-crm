<?php

namespace App\Commands\V2\Project\Journal;

use App\Http\Requests\Api\V2\Project\Project\JournalFilterVariants;

class GetVariantsCommand
{
    public int $project;
    public string $column;

    public function __construct(
        int $project,
        JournalFilterVariants $request,
    )
    {
        $this->project = $project;
        $this->column = $request->column;
    }
}
