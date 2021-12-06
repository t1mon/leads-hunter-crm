<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Notification;
use App\Models\User;
use App\Models\Role;
use App\Models\Project\Project;
use App\Models\Project\UserPermissions;
use App\Models\Project\Email;
use App\Models\Project\TelegramID;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Journal\Facade\Journal;

class ProjectController extends Controller
{

    public function test(){
        return view('material-dashboard.test');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ids = UserPermissions::where(['user_id' => Auth::id()])->pluck('project_id');
        $projects = Project::whereIn('id', $ids)
                                ->with('leads', 'leadsToday')
                                ->withCount('leads', 'leadsToday')
                                ->get();

        return view('material-dashboard.project.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $request->merge([ 'user_id' => Auth::id() ]);
                $project = Project::create($request->only('name', 'user_id'));
                $project->update([ 'api_token' => Str::random(60) ]);
                UserPermissions::create([
                    'user_id' => Auth::id(),
                    'project_id' => $project->id,
                    'role' => Role::ROLE_MANAGER,
                    'view_fields' => ['email', 'city', 'host'],
                ]);
                Notification::create([ 'project_id' => $project->id ]);
            }, 3);  // Повторить три раза, прежде чем признать неудачу
            Journal::project($project->id, 'Проект был создан');
        } catch (\Exception $exception) {
            Journal::projectError($project, 'Ошибка создания проекта: ' . $exception->getMessage());
            Log::error($exception->getMessage());
            return redirect()->route('project.index')->withErrors('Ошибка создания проекта');
        }
        return redirect()->route('project.index')->withSuccess('Проект успешно создан');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Project $project)
    {
        return view('project.show', compact('project'));
    } //show

    public function settings_basic(Project $project, string $tab = null) //Страница основных настроек
    {
        //Проверка полномочий пользователя
        if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');

        //Загрузка хостов
        $hosts = $project->hosts;

        //Загрузка пользователей, назначенных на проект
        $permissions = $project->user_permissions;

        return view('material-dashboard.project.settings_basic', compact('tab', 'project', 'hosts', 'permissions'));
    } //settings_basic

    public function settings_sync(Project $project, string $tab = null) //Страница настроек синхронизации
    {
        //Загрузка списка email-адресов
        $emails = Email::where('project_id', $project->id)->get();

        //TODO Загрузка контактов Telegram
        //Канал
        $telegram_groupID = TelegramID::where(['project_id' => $project->id, 'type' => TelegramID::TYPE_CHANNEL])->first();

        //Личные чаты
        $telegram_privateIDs = TelegramID::where(['project_id' => $project->id, 'type' => TelegramID::TYPE_PRIVATE, ])->paginate(50);

        return view( 'material-dashboard.project.settings_sync',
            compact('tab', 'project', 'emails', 'telegram_groupID', 'telegram_privateIDs') );
    } //sync_settings

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function journal(Request $request, Project $project)
    {
        if (Gate::denies('view', $project)) {
            return redirect()->route('project.index');
        }
        //dd($request->date_from);

        $this->validate($request, [
            'date_from' => 'nullable|date_format:Y-m-d',
            'date_to'   => 'nullable|date_format:Y-m-d',
        ]);

        $leads = $project->leads();

        if ($request->has('date_from') && !empty($request->date_from)) {
            $leads->whereDate('created_at', '>=', Carbon::parse($request->date_from)->format('Y-m-d'));
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $leads->whereDate('created_at', '<=', Carbon::parse($request->date_to)->format('Y-m-d'));
        }

        if ($request->has('double_phone') && !empty(request()->double_phone)) {
            $leads->where('entries', '=', 1);
        }


        $leads = $leads->orderBy('updated_at', 'desc')->paginate(50)->withPath("?" . $request->getQueryString());

        return view('material-dashboard.project.journal', compact('project', 'leads'));
    } //journal

    public function notification(Request $request, Project $project)
    {
        //Проверка полномочий пользователя
        if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');

        //TODO: Валидация запроса даты
        //TODO: Загрузка уведомлений из базы и сортировка их по запросу

        //Получение списка email-адресов
        $emails = Email::where('project_id', $project->id)->get();

        //Получение списка уведомлений
        $notifications =  Notification::where('project_id', $project->id)->get();
        return view('project.notification', compact('emails', 'notifications', 'project'));
    }   //notification

    public function hosts(Request $request, Project $project){
        //Проверка полномочий пользователя
        if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');

        $hosts = $project->hosts;

        return view('project.hosts', compact('hosts', 'project'));
    } //hosts

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    } //edit

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //Проверка полномочий пользователя
        if (Gate::denies('update', [Project::class, $project]))
            return trans('projects.not-authorized');

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
        Journal::project($project, 'Пользователь ' . Auth::user()->name . ' обновил настройки проекта.');
        return back()->withSuccess('Настройки проекта обновлены');
    } //update

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //Проверка полномочий пользователя
        if (Gate::denies('delete', [Project::class, $project]))
            return redirect()->route('project.index');
        $project_log = $project;
        $project->delete();

        Journal::project($project_log, 'Пользователь ' . Auth::user()->name . ' удалил проект.');
        return redirect()->route('project.index')->withSuccess('Проект удален');
    } //destroy

    public function log(Project $project, Request $request){
        //Проверка полномочий пользователя
        if (Gate::denies('delete', [Project::class, $project]))
            return redirect()->route('project.index');

        $entries = null;
        if($request->has('amount')){
            if($request->amount === 'all')
                $entries = Journal::allInProject($project);
            else
                $entries = Journal::recentInProject($project, $request->amount);
        }
        else
            $entries = Journal::recentInProject($project);

        //TODO Отсеивание записей по дате и иным критериям
        //...

        return view('material-dashboard.project.log.index', compact('entries', 'project'));
    } //log
}
