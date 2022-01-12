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
    /*####################
        Служебные методы
    ######################*/
    protected function _response($status, $message, $code){ //Составить тело ответа
        return response()->json([
            'data' => [
                'status' => $status,
                'message' => $message,
                'response' => $code,
            ]], $code);
    } //_response

    /*####################
            CRUD
    ######################*/
    public function index(Request $request)
    { //Получить список проектов, доступных текущему пользователю

        //Загрузка идентификаторов проекта, на которые назначен пользователь
        $project_ids = UserPermissions::where('user_id', Auth::guard('api')->id())->pluck('project_id');

        //Загрузка проектов по идентификаторам
        $projects = Project::whereIn('id', $project_ids)->with('leads')->withCount('leads')->get();

        //Передача полученных данных
        return ProjectCollectionResource::collection($projects);
    } //index

    public function store(Request $request){ //Создать проект
        $user = Auth::guard('api')->user();

        //Попытка создания проекта в DB
        try{
            //Валидация
            $request->validate([
                'name' => 'required|string',
                'color' => ['nullable', 'regex:/^([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/']
                // 'color' => 'nullable|regex:/^\#[\da-f]{3}|\#[\da-f]{6}$'
            ]);

            DB::transaction(function () use ($request, $user) {
                $request->merge([ 'user_id' => $user->id ]);
                $project = Project::create($request->only('name', 'user_id'));

                //Добавление цвета
                $settings = $project->settings;
                $settings['color'] = $request->exists('color') ? $request->color : Project::DEFAULT_COLOR;

                $project->update([ 'api_token' => Str::random(60), 'settings' => $settings]);

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

    public function update(Project $project, Request $request){
        $user =  Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('update', [Project::class, $project]))
            return $this->_response('project_error', 'You are not authorized for this action', Response::HTTP_FORBIDDEN);

        /*Атрибуты проекта делятся на две группы:
            - properties: свойства проекта (имя, токен и т.п.)
            - settings: настройки (telegram, email и часовой пояс)
        */

        //Обновление свойств проекта
        //TODO При добавлении новых свойств необходимо убедиться, что данная инструкция не затрёт другие свойства проекта
        if($request->properties)
            $project->fill($request->all()['properties']);

        //Обновление настроек синхронизации
        if($request->settings){
            $new_settings = $request->all()['settings'];

            $new_settings = array_merge($project->settings, $new_settings);

            $new_settings['email']['enabled'] = (bool) $new_settings['email']['enabled'];
            $new_settings['telegram']['enabled'] = (bool) $new_settings['telegram']['enabled'];

            if(!array_key_exists('fields', $new_settings['email']))
                $new_settings['email']['fields'] = [];

            if(!array_key_exists('fields', $new_settings['telegram']))
                $new_settings['telegram']['fields'] = [];

            $project->settings = $new_settings;
        }

        $project->save();
        Journal::project($project, 'Пользователь ' . $user()->name . ' обновил настройки проекта.');
        return $this->_response('project_update', 'Project has been updated', Response::HTTP_OK);
    } //update

    public function destroy(Project $project, Request $request){
        $user =  Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('delete', [Project::class, $project]))
            return $this->_response('project_error', 'You are not authorized for this action', Response::HTTP_FORBIDDEN);


        $project_log = $project;
        $project->delete();

        Journal::project($project_log, 'Пользователь ' . $user->name . ' удалил проект.');
        return $this->_response('project_deleted', 'Project has been deleted', Response::HTTP_OK);
    } //destroy

    /*####################
        Прочие методы
    ######################*/
    public function journal(Project $project, Request $request){
        $user = Auth::guard('api')->user();

        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('view', $project))
            return $this->_response('project_error', 'You are not authorized for this action', Response::HTTP_FORBIDDEN);

        //Валидация фильтра по датам
        $this->validate($request, [
            'date_from' => 'nullable|date_format:Y-m-d',
            'date_to'   => 'nullable|date_format:Y-m-d',
        ]);


        $leads = $project->leads();

        //Отфильтровка по датам (если они присутствуют в запросе)
        if($request->filled('date_from'))
        {
            $date = Carbon::parse($request->date_from, $project->timezone)->startOfDay()->setTimezone(config('app.timezone'));
            $leads->where('created_at', '>=' ,$date);
        }

        if($request->filled('date_to'))
        {
            $end_date = Carbon::parse($request->date_to, $project->timezone)->endOfDay()->setTimezone(config('app.timezone'));
            $leads->where('created_at', '<=' ,$end_date);
        }

        //Отсеивание дублирующихся лидов (если это указано в запросе)
        if ($request->has('double_phone') && !empty(request()->double_phone)) {
            $leads->where('entries', '=', 1);
        }


        $leads = $leads->orderBy('updated_at', 'desc')->paginate(50)->withPath("?" . $request->getQueryString());
        return new ProjectResource($project, ['leads' => $leads]);
    } //journal

    public function settings_basic(Project $project, Request $request) //Страница основных настроек
    {
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return $this->_response('project_error', 'You are not authorized for this action', Response::HTTP_FORBIDDEN);

        //Загрузка хостов
        $hosts = $project->hosts;

        //Загрузка пользователей, назначенных на проект
        $permissions = $project->user_permissions;

        return new ProjectResource($project, ['hosts' => $hosts, 'permissions' => $permissions]);
    } //settings_basic

    public function settings_sync(Project $project, Request $request) //Страница настроек синхронизации
    {
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return $this->_response('project_error', 'You are not authorized for this action', Response::HTTP_FORBIDDEN);

        //Загрузка списка email-адресов
        $emails = Email::where('project_id', $project->id)->get();

        //TODO Загрузка контактов Telegram
        //Канал
        $telegram_ids['group'] = TelegramID::where(['project_id' => $project->id, 'type' => TelegramID::TYPE_CHANNEL])->first();

        //Личные чаты
        $telegram_ids['private'] = TelegramID::where(['project_id' => $project->id, 'type' => TelegramID::TYPE_PRIVATE, ])->paginate(50);

        return new ProjectResource($project, ['emails' => $emails, 'telegram_ids' => $telegram_ids]);
    } //settings_sync

    public function toggle(Project $project){
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return $this->_response('project_error', 'You are not authorized for this action', Response::HTTP_FORBIDDEN);

        $settings = $project->settings;
        $settings['enabled'] = (bool)(!$settings['enabled']);

        $project->settings = $settings;
        $project->save();

        return response()->json(['message' => 'Project has been ' .  ($project->settings['enabled'] ? 'enabled' : 'disabled')], Response::HTTP_OK);
    }
}
