<?php

namespace App\Commands\V2\Project\Lead\Comment;

use App\Http\Requests\Api\V2\Project\Lead\Comment\DeleteRequest;

class DeleteCommand
{
    public readonly int $comment_id;

    public function __construct(DeleteRequest $request)
    {
        $this->comment_id = $request->comment_id;
    }
}
