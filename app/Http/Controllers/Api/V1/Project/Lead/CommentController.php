<?php

namespace App\Http\Controllers\Api\V1\Project\Lead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Leads;
use App\Models\Project\Project;
use App\Models\Project\Lead\Comment;

use App\Journal\Facade\Journal;

class CommentController extends Controller
{
    public function show(Project $project, Leads $lead, Comment $comment){
        return response()->json(['data' => [
                'project' => $project,
                'lead' => $lead,
                'comment' => $comment
            ]
        ]);
    } //show

    public function store(Project $project, Leads $lead, Request $request){
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if(!$user->isInProject($project))
            return response()->json(['error' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);

        //Валидация
        $request->validate([
            'comment_body' => 'required',
        ]);

        Comment::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'lead_id' => $lead->id,
                'project_id' => $project->id,
            ],
            [
                'comment_body' => $request->comment_body,
            ]
        );

        Journal::lead($lead, $user->name . ' добавил комментарий к лиду: ' . $request->comment_body);
        return response()->json(['message' => 'Comment has been added'], Response::HTTP_OK);
    } //store

    public function destroy(Project $project, Leads $lead, Comment $comment){
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if(!$user->isInProject($project))
            return response()->json(['error' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);

        $comment->delete();
        return response()->json(['message' => 'Comment has been deleted'], Response::HTTP_OK);
    } //delete
}
