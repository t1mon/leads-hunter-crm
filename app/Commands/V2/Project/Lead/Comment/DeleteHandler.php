<?php

namespace App\Commands\V2\Project\Lead\Comment;

use Illuminate\Http\Response;

use App\Repositories\Project\Lead\Comment\Repository as CommentRepository;
use App\Repositories\Project\Lead\Comment\ReadRepository as CommentReadRepository;

class DeleteHandler
{
    /**
     * DeleteHandler constructor.
     */
    public function __construct(
        private CommentRepository $commentRepository,
        private CommentReadRepository $commentReadRepository,
    )
    {
    }

    /**
     * @param DeleteCommand $command
     */
    public function handle(DeleteCommand $command)
    {
        $comment = $this->commentReadRepository->findById(id: $command->comment_id, fail: true);

        $this->commentRepository->remove($comment);

        return response(content: 'Комментарий удалён', status: Response::HTTP_NO_CONTENT);
    }
}
