<?php

namespace App\Http\Controllers\Project\Lead;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Leads;
use App\Models\Project\Project;
use App\Models\Project\Lead\Comment;

class CommentController extends Controller
{
    public function show(Project $project, Leads $lead, Comment $comment){
        return view('material-dashboard.project.comments.show', compact('project', 'lead', 'comment'));
    } //show

    public function create(Project $project, Leads $lead){
        //Проверка полномочий пользователя
        if(!Auth::user()->isInProject($project))
            return redirect()->route('project.index');

        return view('material-dashboard.project.comments.create', compact('project', 'lead'));
    } //create

    public function store(Project $project, Leads $lead, Request $request){
        //Проверка полномочий пользователя
        if(!Auth::user()->isInProject($project))
            return redirect()->route('project.index');

        //TODO Написать более развёрнутую валидацию
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

        return redirect()->route('project.journal', [$project]);
    } //store

    public function edit(Project $project, Leads $lead, Comment $comment){
        //Проверка полномочий пользователя
        if(!Auth::user()->isInProject($project))
            return redirect()->route('project.index');
        
        return view('material-dashboard.project.comments.create', compact('project', 'lead', 'comment'));
    } //edit

    public function update(){
        
    } //update

    public function destroy(Project $project, Leads $lead, Comment $comment){
        //Проверка полномочий пользователя
        if(!Auth::user()->isInProject($project))
            return redirect()->route('project.index');

        $comment->delete();
        return redirect()->route('project.journal', [$project]);
    } //delete
}
