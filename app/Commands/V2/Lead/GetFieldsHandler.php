<?php

namespace App\Commands\V2\Lead;

use App\Models\Leads;

class GetFieldsHandler
{
    /**
     * GetFieldsHandler constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param GetFieldsCommand $command
     */
    public function handle(GetFieldsCommand $command)
    {
        $result = [];
        foreach(Leads::FIELDS as $field)
            $result[$field] = __('leads.fields.'.$field);

        return $result;
    }
}
