<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Leads;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\Project\Project;

use App\Journal\Facade\Journal;

class WebhookController extends Controller
{
    public function store(Project $project, Request $request){
        //TODO Создать Request для валидации

         //Проверка полномочий пользователя
         if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');

        //Проверка существования вебхука
        if( !is_null($project->webhook_get($request->name)) ){
            Journal::projectError($project,
            trans('project.notifications.webhooks.error-create') . ': ' . $request->name . ' – ' . trans('project.notifications.webhooks.error-exists'));
            return redirect()->route('project.settings-sync', ['project' => $project, 'tab' => 'webhooks'])
                ->withError(trans('project.notifications.webhooks.error-create') . ': ' . trans('project.notifications.webhooks.error-exists'));
        }

        //Добавление пустого поля 'fields', если в форме не было указано ни одного поля
        // if(!$request->exists('fields'))
        //     $request->merge(['fields' => [] ]);

        $project->webhook_add($request->except(['_token']));
        $project->save();


        Journal::project($project, Auth::user()->name . ' добавил вебхук ' . $request->name);
        return redirect()->route('project.settings-sync', ['project' => $project, 'tab' => 'webhooks'])
                ->withSuccess( trans('project.notifications.webhooks.create-success') );
    } //store

    public function edit(Project $project, string $webhook_name){
        $webhook = $project->webhook_get($webhook_name);
        $type = $webhook->type;
        return view('material-dashboard.project.webhooks.edit', compact('project', 'webhook', 'type'));
    } //edit

    public function update(Project $project, string $webhook_name, Request $request){
        //TODO Создать Request для валидации

         //Проверка полномочий пользователя
         if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');

        //Добавления пустого поля 'fields', если в форме не было указано ни одного поля
        if(!$request->exists('fields'))
        $request->merge(['fields' => [] ]);

        $project->webhook_update($webhook_name, $request->except('_token', '_method'));

        $project->save();

        Journal::project($project, Auth::user()->name . ' обновил настройки вебхука ' . $request->name);
        return redirect()->route('project.settings-sync', ['project' => $project, 'tab' => 'webhooks'])
            ->withSuccess( trans('project.notifications.webhooks.update-success') );
    } //update

    public function destroy(Project $project, string $webhook_name){
        //Проверка полномочий пользователя
        if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');
        
        $project->webhook_delete($webhook_name);
        $project->save();

        Journal::project($project, Auth::user()->name . ' удалил вебхук ' . $webhook_name);
        return redirect()->route('project.settings-sync', ['project' => $project, 'tab' => 'webhooks'])
                ->withSuccess( trans('project.notifications.webhooks.delete-success') );
    } //destroy

    public function toggle(Project $project, string $webhook_name){
        //Проверка полномочий пользователя
        if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');
        
        $project->webhook_update($webhook_name, ['enabled' => (bool)!$project->settings['webhooks'][$webhook_name]['enabled']]);
        $project->save();

        Journal::project($project, Auth::user()->name
        . ($project->settings['webhooks'][$webhook_name]['enabled'] === true ? ' включил' : ' выключил')
        . ' вебхук ' . $webhook_name);
        return redirect()->route('project.settings-sync', ['project' => $project, 'tab' => 'webhooks']);
    }

    public function test(){
        
        $lead = Leads::find(102);
        return $lead->project->webhook_send('Новый вебхук', $lead);
        
        
        // $string = <<<EOD
        //     fields:
        //       TITLE: 'Заявка с компании L-Digital'
        //       NAME: '\$name'
        //       STATUS_ID: 'NEW'
        //       SOURCE_ID: '79626114910'
        //       SOURCE_DESCRIPTION: 'L-Digital'
        //       OPENED: 'Y'
        //       PHONE:
        //         -
        //           VALUE: '\$phone'
        //           VALUE_TYPE: 'WORK'
        //       EMAIL:
        //         -
        //           VALUE: '\$email'
        //           VALUE_TYPE: 'WORK'
        //     params:
        //       - REGISTER_SONET_EVENT: 'Y'
        // EOD;

        // $fields = ['name', 'phone', 'email'];

        // foreach($fields as $field){
        //     $string = str_replace('$'.$field, $lead->$field, $string);
        // }

        // $result = yaml_parse($string);
        // return $result;
    } //test

}
