<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Requests\EmailRequest;

use App\Models\User;
use App\Models\Project\Email;
use App\Models\Project\Project;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Journal\Facade\Journal;

class EmailController extends Controller
{
    //Возможно, эта функция понадобится при управлении через API
    public function index(Project $project, Request $request){
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);
        
        $emails = Email::where('project_id', $project->id)->get(['id', 'email']);
        return response()->json(['data' => $emails], Response::HTTP_OK);
    } //index

    public function store(Project $project, EmailRequest $request){
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);
        
        try{
            $request->validate(['email' => 'required|email']);

             //Если указанный адрес уже существует и привязан к уже существующему проекту, не создавать его
            if(Email::where(['email' => $request->email, 'project_id' => $project->id])->exists())
                throw new \Exception('E-mail '.$request->email.' already exists');
            $request->merge(['project_id' => $project->id]);
            $email = Email::create($request->all());
            Journal::project(Project::find($project->id), $user->name . ' добавил e-mail ' . $email->email);
        }
        catch(\Exception $exception){
            Log::error($exception->getMessage());
            Journal::projectError(Project::find($project->id), $exception->getMessage());
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        }
        return response()->json(['message' => 'Email has been created'], Response::HTTP_CREATED);
    } //store

    public function destroy(Project $project, Email $email, Request $request){
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);

        $email_name = $email->email;
        $email->delete();
        Journal::project($project, 'Пользователь ' . $user->name . ' удалил e-mail ' . $email->email);
        return response()->json(['message' => 'Email has been deleted'], Response::HTTP_OK);
    } //destroy
}
