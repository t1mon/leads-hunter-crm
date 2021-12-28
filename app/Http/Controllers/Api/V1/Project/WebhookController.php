<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use App\Models\Project\Project;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Journal\Facade\Journal;

class WebhookController extends Controller
{
    //Возможно, эта функция понадобится при управлении через API
    public function index(Project $project, Request $request){
        $user =  User::where('api_token', $request->bearerToken())->first();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);
        
        return response()->json([
            'data' => collect($project->webhooks),
        ], Response::HTTP_OK);
    } //index
}
