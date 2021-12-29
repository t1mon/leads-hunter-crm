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

    }
}
