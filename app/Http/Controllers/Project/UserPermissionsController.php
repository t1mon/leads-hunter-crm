<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;

use App\Models\Role;
use App\Models\User;
use App\Models\Project\UserPermissions;
use App\Models\Project;

use Illuminate\Http\Request;

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
        if(Gate::denies('view', $project))
            return 'У вас нет полномочий на просмотр данной страницы'; //TODO: сделать более симпатичное представление
        
        //TODO: Проверка наличия разрешений пользователя в БД (если не записаны, загрузить значения по умолчанию)
        //...
        
        $permissions = $project->user_permissions;

        return view('project.users', compact('project', 'permissions'));
    } //list

    public function store(Request $request)
    {
        $project = Project::find($request->project_id);

        //Проверка полномочий пользователя
        if($project->isAdmin() or $project->isOwner()){
            $new_user = User::firstOrCreate(['email', $request->email]);
            


        }
        else
            return 'У вас нет полномочий на данное действие';

    } //store
}
