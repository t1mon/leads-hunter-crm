<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\Project as ProjectResource;
use App\Http\Resources\ProjectCollection as ProjectCollectionResource;

use App\Models\Notification;
use App\Models\User;
use App\Models\Role;
use App\Models\Project\Project;
use App\Models\Project\UserPermissions;
use App\Models\Project\Email;
use App\Models\Project\TelegramID;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

use App\Journal\Facade\Journal;
use App\Exports\LogsExportToday;
use App\Exports\LeadExport;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{
    public function index(Request $request){ //Получить список проектов, доступных текущему пользователю
        //Проверка на наличия токена в запросе
        if(!$request->has('api_token')){
            Journal::error('Неудачная попытка запроса по api: не указан токен');
            return response()->json([
                'data' => [
                    'status' => 'no_token_given',
                    'message' => 'No API token is given within the request',
                    'response' => Response::HTTP_PRECONDITION_FAILED,
                ],
            ], Response::HTTP_PRECONDITION_FAILED);
        }

        //Загрузка данных пользователя по API_token
        $user = User::where('api_token', $request->api_token)->first();
        if(is_null($user)){
            Journal::error('Неудачная попытка запроса по api: '.$request->api_token);

            return response()->json([
                'data' => [
                    'status' => User::USER_NOT_FOUND,
                    'message' => 'No user with such API token',
                    'response' => Response::HTTP_PRECONDITION_FAILED,
                ],
            ], Response::HTTP_PRECONDITION_FAILED);
        }

        //Загрузка идентификаторов проекта, на которые назначен пользователь
        $project_ids = UserPermissions::where('user_id', $user->id)->pluck('project_id');

        //Загрузка проектов по идентификаторов
        $projects = Project::whereIn('id', $project_ids)->with('leads')->withCount('leads')->get();

        //Передача полученных данных
        return new ProjectCollectionResource($projects);
    } //index
}
