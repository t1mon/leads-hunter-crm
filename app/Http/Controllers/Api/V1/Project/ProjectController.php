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
    protected function _checkApiToken(Request|ProjectRequest $request){ //Проверка существования API-токена
        //Проверка на наличия токена в запросе
        if(!$request->filled('api_token')){
            Journal::error('Неудачная попытка запроса по api: не указан токен');
            return ['success' => false, 'response' => [
                response()->json([
                    'data' => [
                        'status' => 'no_token_given',
                        'message' => 'No API token is given within the request',
                        'response' => Response::HTTP_PRECONDITION_FAILED,
                    ],
                ], Response::HTTP_PRECONDITION_FAILED)
            ]];
        }

        //Загрузка данных пользователя по API_token
        $user = User::where('api_token', $request->api_token)->first();
        if(is_null($user)){
            Journal::error('Неудачная попытка запроса по api: '.$request->api_token);
            return ['success' => false, 'response' => [
                response()->json([
                    'data' => [
                        'status' => User::USER_NOT_FOUND,
                        'message' => 'No user with such API token',
                        'response' => Response::HTTP_PRECONDITION_FAILED,
                    ],
                ], Response::HTTP_PRECONDITION_FAILED)
            ]];
        }

        return ['success' => true, 'user' => $user];
    } //_checkApiToken

    protected function _response($status, $message, $code){ //Составить тело ответа
        return response()->json([
            'data' => [
                'status' => $status,
                'message' => $message,
                'response' => $code,
            ]], $code);
    } //_response

    public function index(Request $request){ //Получить список проектов, доступных текущему пользователю
        //Проверка существования токена и загрузка пользователя
        $check = $this->_checkApiToken($request);
        if(!$check['success']) return $check['response'];

        $user = $check['user'];

        //Загрузка идентификаторов проекта, на которые назначен пользователь
        $project_ids = UserPermissions::where('user_id', $user->id)->pluck('project_id');

        //Загрузка проектов по идентификаторов
        $projects = Project::whereIn('id', $project_ids)->with('leads')->withCount('leads')->get();

        //Передача полученных данных
        return new ProjectCollectionResource($projects);
    } //index

    public function store(Request $request){ //Создать проект
        //Проверка существования токена и загрузка пользователя
        $check = $this->_checkApiToken($request);
        if(!$check['success']) return $check['response'];

        $user = $check['user'];

        //Попытка создания проекта в DB
        try{
            DB::transaction(function () use ($request, $user) {
                $request->merge([ 'user_id' => $user->id ]);
                $project = Project::create($request->only('name', 'user_id'));
                $project->update([ 'api_token' => Str::random(60) ]);
                
                //Создание разрешений пользователя на проект (создатель по умолчанию имеет все полномочия)
                UserPermissions::create([
                    'user_id' => $user->id,
                    'project_id' => $project->id,
                    'role' => Role::ROLE_MANAGER,
                    'view_fields' => ['email', 'city', 'cost', 'host', 'referrer', 'comment', 'utm_source', 'utm_medium', 'utm_campaign', 'source'],
                ]);
                Notification::create([ 'project_id' => $project->id ]);
                Journal::project($project, $user->name . ' создал проект.');
            }, 3);  // Повторить три раза, прежде чем признать неудачу

            //Вернуть ответ
            return $this->_response('project_created', 'Project has been created', Response::HTTP_CREATED);
        }
        catch(\Exception $exception){
            Journal::error('Ошибка создания проекта: "' . $request->name . '": ' . $exception->getMessage());
            Log::error($exception->getMessage());
            //Вернуть ответ
            return $this->_response('project_error', 'Project cannot be created', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    } //store

    public function destroy(Request $request){
        //Проверка существования токена и загрузка пользователя
        $check = $this->_checkApiToken($request);
        if(!$check['success']) return $check['response'];

        $user = $check['user'];

        //Проверка наличия в запросе номера проекта
        if(!$request->filled('project_id'))
            return $this->_response('project_error', 'No project ID given within the request', Response::HTTP_PRECONDITION_FAILED);
        
        //Проверка существования проекта
        $project = Project::findOrFail($request->project_id);
        $project->delete();

        return $this->_response('project_deleted', 'Project was deleted', Response::HTTP_OK);
    } //destroy
}
