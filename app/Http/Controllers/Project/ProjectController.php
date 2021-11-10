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
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
                    'role_id' => Role::ROLE_MANAGER_ID,
                    'view_fields' => ['email', 'city', 'host'],
                ]);
                Notification::create([ 'project_id' => $project->id ]);
            }, 3);  // Повторить три раза, прежде чем признать неудачу
        } catch (\Exception $exception) {
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
        //...

        return view('material-dashboard.project.settings_sync', compact('tab', 'project', 'emails'));
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

        //Обновление настроек
        $new_settings = $request->all()['settings'];
        $new_settings['email']['enabled'] = (bool) $new_settings['email']['enabled'];
        if(!array_key_exists('fields', $new_settings['email']))
            $new_settings['email']['fields'] = [];

        $project->settings = $new_settings;

        $project->save();
        return redirect()->route('project.settings-sync', $project)->withSuccess('Настройки проекта обновлены');
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

        $project->delete();

        return redirect()->route('project.index')->withSuccess('Проект удален');
    } //destroy
}
