<?php

namespace App\Commands\V2\Lead\CUD;

use App\Models\Project\Project;

class AddCommand
{
    /**
     * AddCommand constructor.
     */
    public function __construct(
        public Project|int $project,
        public string $name,
        public int $phone,
        public ?string $host = null,
        public ?string $surname = null,
        public ?string $patronymic = null,
        public ?string $owner = null,
        public ?string $cost = null,
        public ?string $email = null,
        public ?string $comment = null,
        public ?string $manual_city = null,
        public ?string $manual_region = null,
        public ?string $company = null,
        public ?string $ip = null,
        public ?string $referrer = null,
        public ?string $source = null,
        public ?string $utm_medium = null,
        public ?string $utm_campaign = null,
        public ?string $utm_source = null,
        public ?string $utm_term = null,
        public ?string $utm_content = null,
        public ?string $url_query_string = null,
        public ?string $nextcall_date = null,
    )
    {
    }
}
