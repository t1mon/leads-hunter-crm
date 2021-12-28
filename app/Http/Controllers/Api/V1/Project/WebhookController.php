<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use App\Models\Project\Project;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Journal\Facade\Journal;

class WebhookController extends Controller
{
    //Возможно, эта функция понадобится при управлении через API
    public function index(Project $project, Request $request){
        $user =  Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);
        
        $webhooks = collect($project->webhooks)->map(function($item, $key){
            return ['name' => $item->name, 'type' => $item->type];
        });

        return response()->json(['data' => $webhooks], Response::HTTP_OK);
    } //index

    public function store(Project $project, Request $request){
        //TODO Создать Request для валидации
        //..
        
        $user =  Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);
        
        try{
            //Проверка существования вебхука
            if( !is_null($project->webhook_get($request->name)) )
                throw new \Exception('Webhook already exists');
            
            $request->merge(['query' => yaml_emit($request->fields)]);
            $project->webhook_add($request->except('_token', 'fields', 'form'));
            $project->save();
        }
        catch(\Exception $exception){
            Journal::projectError($project, $exception->getMessage());
            return response()->json(['error' => $exception->getMessage()]);
        }

        Journal::project($project, $user->name . ' добавил вебхук ' . $request->name);
        return response()->json(['message' => 'Webhook has been created'], Response::HTTP_CREATED);
    } //store

    public function destroy(Project $project, string $webhook_name, Request $request){
        //TODO Создать Request для валидации
        $user =  Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);
        
        try{
            //Проверка существования вебхука
            if( is_null($project->webhook_get($webhook_name)) )
                throw new \Exception('The webhook doesn\'t exist');
            
            $project->webhook_delete($webhook_name);
            $project->save();
        }
        catch(\Exception $exception){
            Journal::projectError($project, $exception->getMessage());
            return response()->json(['error' => $exception->getMessage()]);
        }
        
        Journal::project($project, $user->name . ' удалил вебхук ' . $webhook_name);
        return response()->json(['message' => 'Webhook has been deleted'], Response::HTTP_OK);
    } //destroy
}
