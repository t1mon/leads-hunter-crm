<?php

namespace App\Commands\V2\Project\Journal;

use App\Http\Requests\Api\V2\Project\Project\Journal as JournalRequest;

class JournalCommand
{
    public $project_id;
    public $date_from;
    public $date_to;
    public $entries;
    public $owner;
    public $phone;
    public $email;
    public $cost_from;
    public $cost_to;
    public $referrer;
    public $city;
    public $source;
    public $utm_medium;
    public $utm_source;
    public $utm_campaign;
    public $utm_content;
    public $host;
    public $url_query_string;

    public $user;

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
        $this->entries = $request->entry_filter;
        $this->owner = $request->owner;
        $this->phone = $request->phone;
        $this->email = $request->email;
        $this->cost_from = $request->cost_from;
        $this->cost_to = $request->cost_to;
        $this->referrer = $request->referrer;
        $this->city = $request->city;
        $this->source = $request->source;
        $this->utm_medium = $request->utm_medium;
        $this->utm_source = $request->utm_source;
        $this->utm_campaign = $request->utm_campaign;
        $this->utm_content = $request->utm_content;
        $this->host = $request->host;
        $this->url_query_string = $request->url_query_string;

        $this->user = $request->user();
    }
}
