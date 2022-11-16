<?php

namespace App\Commands\V2\Project\Journal;

use App\Http\Requests\Api\V2\Project\Project\Journal as JournalRequest;

class JournalCommand
{
    public $project_id;
    public $date_from;
    public $date_to;
    public $owner;
    public $entries;
    public $host;
    public $city;
    public $source;
    public $cost_from;
    public $cost_to;

    /**
     * JournalCommand constructor.
     */
    public function __construct(
        int $project_id,
        JournalRequest $request
    )
    {
        $this->project_id = $project_id;
        $this->date_from = $request->date_from;
        $this->date_to = $request->date_to;
        $this->owner = $request->owner;
        $this->entries = $request->entry_filter;
        $this->host = $request->host;
        $this->city = $request->city;
        $this->source = $request->source;
        $this->cost_from = $request->cost_from;
        $this->cost_to = $request->cost_to;
    }
}
