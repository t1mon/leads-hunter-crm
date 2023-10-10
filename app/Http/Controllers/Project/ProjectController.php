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
use App\Exports\LogsExportToday;
use App\Exports\LeadExport;
use App\Jobs\ExportLeadsToMail;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $ids = UserPermissions::where(['user_id' => Auth::id()])->pluck('project_id');
//         $projects = Project::whereIn('id', $ids)
//                                 ->with('leads')
//                                 ->withCount('leads')
//                                 ->get();
//
//        return view('material-dashboard.project.index', compact('projects'));
        return view('material-dashboard.project.index');
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
                    'view_fields' => ['email', 'city', 'cost', 'host', 'referrer', 'utm_source', 'utm_medium', 'utm_campaign', 'source'],
                ]);

                //Добавить количество лидов в проекте
                DB::table('leads_count')->insert([
                    'project_id' => $project->id,
                    'total_leads' => 0,
                    'leads_today' => 0,
                ]);

                Notification::create([ 'project_id' => $project->id ]);
                Journal::project($project, Auth::user()->name . ' создал проект.');
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

        //Загрузка данных
        $project->load(['user', 'user_permissions', 'calltracking_phones', 'classes', 'hosts']);

        $hosts = $project->hosts;
        $calltracking_phones = $project->calltracking_phones;

        //Загрузка пользователей, назначенных на проект
        $permissions = $project->user_permissions;

        return view('material-dashboard.project.settings_basic', compact('tab', 'project', 'hosts', 'permissions', 'calltracking_phones'));
    } //settings_basic

    public function settings_sync(Project $project, string $tab = null) //Страница настроек синхронизации
    {
        //Проверка полномочий пользователя
        if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');

        //Загрузка списка email-адресов
        $emails = Email::where('project_id', $project->id)->get();

        //TODO Загрузка контактов Telegram
        //Канал
        $telegram_groupID = TelegramID::where(['project_id' => $project->id, 'type' => TelegramID::TYPE_CHANNEL])->first();

        //Личные чаты
        $telegram_privateIDs = TelegramID::where(['project_id' => $project->id, 'type' => TelegramID::TYPE_PRIVATE, ])->paginate(50);

        //Поля лидов
        $lead_fields = \App\Models\Leads::getFields();
        array_push($lead_fields, 'comment_crm');
        unset($lead_fields[0]);
        unset($lead_fields[3]);
        unset($lead_fields[4]);
        unset($lead_fields[5]);
        unset($lead_fields[7]);
        unset($lead_fields[13]);
        unset($lead_fields[17]);
        unset($lead_fields[24]);
        unset($lead_fields[26]);

        return view( 'material-dashboard.project.settings_sync',
            compact('tab', 'project', 'emails', 'telegram_groupID', 'telegram_privateIDs', 'lead_fields') );
    } //settings_sync

    public function integrations(Project $project){ //Страница интеграций
        $project->vk_forms;
        return view('material-dashboard.project.integrations.index', compact('project'));
    } //integrations

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function journal(Project $project, Request $request)
    {
        $projectId = $project->id;
        if (Gate::denies('view', $project)) {
            return redirect()->route('project.index');
        }
        //dd($request->date_from);

//        $this->validate($request, [
//            'date_from' => 'nullable|date_format:Y-m-d',
//            'date_to'   => 'nullable|date_format:Y-m-d',
//        ]);
//
//        $leads = $project->leads();
//
//
//        if($request->filled('date_from'))
//        {
//            $date = Carbon::parse($request->date_from, $project->timezone)->startOfDay()->setTimezone(config('app.timezone'));
//            $leads->where('created_at', '>=' ,$date);
//        }
//
//        if($request->filled('date_to'))
//        {
//            $end_date = Carbon::parse($request->date_to, $project->timezone)->endOfDay()->setTimezone(config('app.timezone'));
//            $leads->where('created_at', '<=' ,$end_date);
//        }
//
//        if ($request->has('double_phone') && !empty(request()->double_phone)) {
//            $leads->where('entries', '=', 1);
//        }
//        $leads = $leads->orderBy('updated_at', 'desc')->paginate(50)->withPath("?" . $request->getQueryString());
        return view('material-dashboard.project.journal', compact('project', 'projectId'));
    } //journal

    public function journal_export(Project $project, Request $request){
        //Проверка полномочий
        if(!Auth::user()->isInProject($project))
            return redirect()->route('project.index');

        //Валидация
        $this->validate($request, [
            'date_from' => 'nullable|date_format:Y-m-d',
            'date_to'   => 'nullable|date_format:Y-m-d',
        ]);

        //Запись в лог
        Log::channel('exports')->info(
            message: 'Пользователь ' . auth()->user()->email . ' запросил экспорт проекта ' . $project->name,
            context: array_merge(
             ['datetime' => Carbon::now('Europe/Samara')->format('d.m.Y, H:i:s')],
             $_SERVER,
            ),
        );

        //Создание дат (если указаны в форме)
        $date_from = $request->filled('date_from') ? Carbon::parse($request->date_from, $project->timezone)->startOfDay()->setTimezone(config('app.timezone')) : null;
        $date_to = $request->filled('date_to') ? Carbon::parse($request->date_to, $project->timezone)->endOfDay()->setTimezone(config('app.timezone')) : null;

        $format = $request->has('format') ? $request->format : \Maatwebsite\Excel\Excel::XLSX;

        if($request->method === 'today')
            return (new LeadExport)->today($project)
                ->download(Carbon::today($project->timezone)->format('d-m-Y ').$project->name.'.'.$format, $format);

        //Составление названия файла
        $filename = (is_null($date_from)
                    ? Carbon::parse($project->leads->min('created_at'))->setTimezone($project->timezone)->format('d-m-Y')
                    : $date_from->setTimezone($project->timezone)->format('d-m-Y')) . '-' .
                    (is_null($date_to)
                    ? Carbon::parse($project->leads->max('created_at'))->setTimezone($project->timezone)->format('d-m-Y')
                    : $date_to->setTimezone($project->timezone)->format('d-m-Y ')) . ' Project ' . $project->id;

        return (new LeadExport)->asOfDate($project, $date_from, $date_to)
            ->download($filename.".".$format, $format);
    } //journal_export

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

        $entries = Journal::allInProject($project, $request->type ?? null);

        return view('material-dashboard.project.log.index', compact('entries', 'project'));
    } //log

    public function log_export(Project $project, Request $request){
        //Проверка полномочий пользователя
        if (Gate::denies('delete', [Project::class, $project]))
            return redirect()->route('project.index');

        $method = $request->has('method') ? $request->method : 'all';
        $format = $request->has('format') ? $request->format : \Maatwebsite\Excel\Excel::XLSX;

        return (new LogsExportToday)->$method($project)
            ->download(Carbon::today($project->timezone)->format('d-m-Y ').$project->name.'.'.$format, $format);
    } //downloadLog
}
