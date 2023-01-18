<?php

namespace App\Commands\V2\Project\Journal;

use App\Http\Requests\Api\V2\Project\Project\Journal as JournalRequest;

class JournalCommand
{
    public $project_id;
    public $class;
    public $name;
    public $date_from;
    public $date_to;
    public $nextcall_from;
    public $nextcall_to;
    public $entries;
    public $owner;
    public $phone;
    public $email;
    public $cost_from;
    public $cost_to;
    public $referrer;
    public $city;
    public $company;
    public $region;
    public $manual_region;
    public $source;
    public $utm_medium;
    public $utm_source;
    public $utm_campaign;
    public $utm_content;
    public $utm_term;
    public $host;
    public $url_query_string;

    public $sort_by;
    public $sort_order;

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
        $this->name = $request->name;
        $this->class = $request->class;
        $this->date_from = $request->date_from;
        $this->date_to = $request->date_to;
        $this->nextcall_from = $request->nextcall_from;
        $this->nextcall_to = $request->nextcall_to;
        $this->entries = $request->entry_filter;
        $this->owner = $request->owner;
        $this->phone = $request->phone;
        $this->email = $request->email;
        $this->cost_from = $request->cost_from;
        $this->cost_to = $request->cost_to;
        $this->referrer = $request->referrer;
        $this->city = $request->city;
        $this->company = $request->company;
        $this->region = $request->region;
        $this->manual_region = $request->manual_region;
        $this->source = $request->source;
        $this->utm_medium = $request->utm_medium;
        $this->utm_source = $request->utm_source;
        $this->utm_campaign = $request->utm_campaign;
        $this->utm_content = $request->utm_content;
        $this->utm_term = $request->utm_term;
        $this->host = $request->host;
        $this->url_query_string = $request->url_query_string;
        $this->sort_by = $request->sort_by;
        $this->sort_order = $request->sort_order;

        $this->user = $request->user();
    }
}
