<?php

namespace App\Commands\V2\Project\Lead\Comment;

use App\Http\Resources\Api\V2\Project\Lead\Comment\ShowResource;
use App\Repositories\Project\Lead\Comment\ReadRepository as CommentReadRepository;

class ShowHandler
{
    /**
     * ShowHandler constructor.
     */
    public function __construct(
        private CommentReadRepository $commentReadRepository,
    )
    {
    }

    /**
     * @param ShowCommand $command
     */
    public function handle(ShowCommand $command)
    {
        $comment = $this->commentReadRepository->findById(id: $command->comment_id, fail: true);
        return new ShowResource($comment);
    }
}
