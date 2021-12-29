<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Requests\HostRequest;

use App\Models\Project\Host;
use App\Models\User;
use App\Models\Project\Project;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Journal\Facade\Journal;

class HostController extends Controller
{
    //Возможно, эта функция понадобится при управлении через API
    public function index(Project $project, Request $request){
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);
        
        $hosts = Host::where('project_id', $project->id)->get(['id', 'host']);
        return response()->json(['data' => $hosts], Response::HTTP_OK);
    } //index
    
    public function store(HostRequest $request)
    {
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);

        if(filter_var($request->host, FILTER_VALIDATE_URL)){
            $host = parse_url($request->host);
            $request->merge([
                'host' => $host['host'],
            ]);
        }
        $request->merge(['host' =>  Str::lower($request->host)]); //Перевод в нижний регистр

        try {
            if (Host::where(['host' => $request->host, 'project_id' => $request->project_id])->exists()) {
                throw new \Exception(trans('projects.hosts.create-error') . ' ' . $request->host . ': ' . trans('projects.hosts.error-exists'));
            }
            Host::create($request->all());
        } catch (\Exception $exception) {
            Journal::projectError(Project::find($request->project_id), $exception->getMessage());
            Log::error($exception->getMessage());
            return response()->json(['message' => 'Such host already exists'], Response::HTTP_PRECONDITION_FAILED);
        }
        Journal::project(Project::find($request->project_id), $user->name . ' добавил хост ' . $request->host);
        return response()->json(['message' => 'Host has been created'], Response::HTTP_CREATED);
    } //store

    public function destroy(Project $project, Host $host, Request $request)
    {
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);

        $name = $host->host;

        $host->delete();
        Journal::project($project, $user->name . ' удалил хост ' . $name);
        return response()->json(['message' => 'Host has been deleted'], Response::HTTP_OK);
    } //destroy
}
