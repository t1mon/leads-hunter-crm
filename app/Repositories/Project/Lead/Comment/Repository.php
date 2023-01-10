<?php

namespace App\Repositories\Project\Lead\Comment;

use App\Models\Leads;
use App\Models\Project\Lead\Comment;
use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class Repository{
    public function query(): Builder
    {
        return Comment::query();
    } //query

    public function create(
        User $user,
        Leads $lead,
        string $comment_body,
        ?Project $project = null,
    ): Comment
    {
        return Comment::updateOrCreate(
            [
                'lead_id' => $lead->id,
                'project_id' => $project?->id ?? $lead->project->id,
            ],

            [
                'user_id' => $user->id,
                'comment_body' => $comment_body,
            ]
        );
    } //create

    public function update(
        Comment $comment,
        User $user,
        string $comment_body,
    ): Comment
    {
        $comment->update([
            'user_id' => $user->id,
            'comment_body' => $comment_body,
        ]);

        return $comment;
    } //update

    public function remove(Comment $comment): void
    {
        $comment->delete();
    } //remove

};

?>