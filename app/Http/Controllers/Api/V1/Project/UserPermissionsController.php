<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Role;
use App\Models\User;
use App\Models\Project\UserPermissions;
use App\Models\Project\Project;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

use App\Journal\Facade\Journal;

class UserPermissionsController extends Controller
{
    //Возможно, этот метод пригодится при управлении через API
    public function index(Request $request){ //Список разрешений пользователя
        $permissions = UserPermissions::where('user_id', Auth::guard('api')->id())->get();

        return response()->json(['data' => $permissions], Response::HTTP_OK);
    } //index

    
    public function store(Project $project, Request $request){
        //TODO Сделать пользовательский request и валидацию
        $current_user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($current_user)->denies('settings', $project))
            return response()->json(['error' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);


        //Проверка существования пользователя. Если пользователя нет в БД, вернуть ошибку
        $user = User::where(['email' => $request->email])->first();
        if( is_null($user) )
            return response()->json(['error' => 'No user with such e-mail'], Response::HTTP_NOT_FOUND);
        
        //Если пользователь уже назначен на проект, вернуть ошибку
        if( UserPermissions::where(['user_id' => $user->id, 'project_id' => $project->id])->exists() )
            return response()->json(['error' => 'User is already assigned to this project'], Response::HTTP_NOT_FOUND);
        
        $request->merge(['user_id' => $user->id]);

        UserPermissions::create($request->all());

        Journal::project($project, $current_user->name . ' добавил пользователя ' . $user->name . 'в проект (' . $request->role . ')');

        return response()->json(['message' => 'User has been assigned to the project'], Response::HTTP_OK);
    } //store

    public function update(Project $project, $permissions, Request $request){
        //TODO Сделать пользовательский request и валидацию
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['error' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);

        $permissions = UserPermissions::findOrFail($permissions);
        
        //Полномочия создателя проекта изменять нельзя
        if($permissions->user_id === $project->user_id)
            return response()->json(['error' => "Permissions of the project owner cannot be changed"], Response::HTTP_FORBIDDEN);
        
        $permissions->fill($request->all());

        //Отсеивание пустых значений из view_fields
        $view_fields = Arr::where($permissions->view_fields, function($value, $key){
            return !is_null($value);
        });

        $permissions->view_fields = $view_fields;

        $permissions->save();

        Journal::project($project, $user->name . ' изменил полномочия пользователя ' . User::find($permissions->user_id)->name);
        return response()->json(['message' => 'User permissions have been updated'], Response::HTTP_OK);
    } //update

    public function destroy(Project $project, $permissions, Request $request)
    {
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['error' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);

        $permissions = UserPermissions::findOrFail($permissions);

        //Полномочия создателя проекта удалять нельзя
        if($permissions->user_id === $project->user_id)
            return response()->json(['error' => "Permissions of the project owner cannot be changed"], Response::HTTP_FORBIDDEN);


        $id = $permissions->user_id;
        $permissions->delete();

        Journal::project($project, $user->name . ' удалил пользователя ' . User::find($id)->name . ' из проекта.');
        return response()->json(['message' => 'User permissions have been deleted'], Response::HTTP_OK);
    } //destroy
}
