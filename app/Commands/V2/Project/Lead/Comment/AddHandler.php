<?php

namespace App\Commands\V2\Project\Lead\Comment;

use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use App\Repositories\Project\Lead\Comment\Repository as CommentRepository;
use Illuminate\Http\Response;

class AddHandler
{
    /**
     * AddHandler constructor.
     */
    public function __construct(
        private LeadReadRepository $leadReadRepository,
        private CommentRepository $commentRepository,
    )
    {
    }

    /**
     * @param AddCommand $command
     */
    public function handle(AddCommand $command)
    {
        $lead = $this->leadReadRepository->findById(id: $command->lead_id, fail: true);

        $this->commentRepository->create(
            user: $command->user,
            lead: $lead,
            comment_body: $command->comment_body,
        );

        return response(content: 'Комментарий добавлен', status: Response::HTTP_CREATED);
    }
}
