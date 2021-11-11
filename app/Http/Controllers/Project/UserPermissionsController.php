<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;

use App\Models\Role;
use App\Models\User;
use App\Models\Project\UserPermissions;
use App\Models\Project\Project;

use Illuminate\Http\Request;
use App\Http\Requests\EmailRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class UserPermissionsController extends Controller
{

    public function index()
    {
        //TODO: Полный список всех разрешений для всех пользователей во всех проектах
    } //index

    public function list(Project $project) //Список пользователей и полномочий по проекту
    {
        //Проверка полномочий пользователя
        if(Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');
        
        $permissions = $project->user_permissions;

        return view('project.users', compact('project', 'permissions'));
    } //list

    public function store(EmailRequest $request)
    {
        $project = Project::find($request->project_id);

        //Проверка полномочий пользователя
        if(Gate::denies('create', [UserPermissions::class, $project]))
           return trans('projects.not-authorized'); //TODO: сделать более симпатичное представление;

        //Проверка существования пользователя. Если пользователя нет в БД, вернуть ошибку
        $user = User::where(['email' => $request->email])->first();
        if( is_null($user) )
            return redirect()->route('project.settings-basic', [$project, 'tab' => 'users'])->withErrors(trans('projects.users.create-error') . ': ' . trans('projects.users.error-doesnt-exist'));
        
        //Если пользователь уже назначен на проект, вернуть ошибку
        if( UserPermissions::where(['user_id' => $user->id, 'project_id' => $project->id])->exists() )
            return redirect()->route('project.settings-basic', [$project, 'tab' => 'users'])->withErrors( trans('projects.users.create-error') . ': ' . trans('projects.users.error-exists') );
        
        $request->merge(['user_id' => $user->id]);

        UserPermissions::create($request->all());
        return redirect()->route('project.settings-basic', [$project, 'tab' => 'users'])->withSuccess( trans('projects.users.create-success') );
    } //store

    public function update(Project $project, $permissions, Request $request){
        //Проверка полномочий пользователя
        if(Gate::denies('delete', [UserPermissions::class, $project]))
            return trans('projects.not-authorized'); //TODO: сделать более симпатичное представление;

        //TODO Эта строка поставлена как костыль, пока не будет понятно, почему в этом контроллере $project передаётся как просто идентификатор, а не объект
        $permissions = UserPermissions::find($permissions);

        //Полномочия создателя проекта изменять нельзя
        if($permissions->user_id == $project->user_id)
            return 'Полномочия создателя проекта изменять нельзя'; //TODO: сделать более симпатичное представление

        $permissions->fill($request->all());

        $permissions->save();

        return redirect()->route('project.settings-basic', [$project, 'tab' => 'users'])->withSuccess( trans('projects.users.update-success') );;
    } //update

    public function destroy(Project $project, $permissions)
    {
        //Проверка полномочий пользователя
        if(Gate::denies('delete', [UserPermissions::class, $project]))
            return trans('projects.not-authorized'); //TODO: сделать более симпатичное представление;
        
        //TODO Эта строка поставлена как костыль, пока не будет понятно, почему в этом контроллере $project передаётся как просто идентификатор, а не объект
        $permissions = UserPermissions::find($permissions);

        //Полномочия создателя проекта удалять нельзя
        if($permissions->user_id == $project->user_id)
            return 'Полномочия создателя проекта удалять нельзя'; //TODO: сделать более симпатичное представление

        $permissions->delete();

        return redirect()->route('project.settings-basic', [$project, 'tab' => 'users'])->withSuccess(trans('projects.users.delete-success'));
    } //destroy
}
