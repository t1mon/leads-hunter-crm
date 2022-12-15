<?php

namespace App\Commands\V2\Project\Lead\Comment;

use App\Http\Requests\Api\V2\Project\Lead\Comment\AddRequest;
use App\Models\User;

class AddCommand
{
    public User $user;
    public int $lead_id;
    public string $comment_body;

    public function __construct(AddRequest $request)
    {
        $this->user = $request->user();
        $this->lead_id = $request->lead_id;
        $this->comment_body = $request->comment_body;
    }
}
