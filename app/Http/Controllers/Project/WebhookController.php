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

class WebhookController extends Controller
{
    public function store(Project $project, Request $request){
        //TODO Создать Request для валидации

         //Проверка полномочий пользователя
         if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');

        //Проверка существования вебхука
        if( !is_null($project->webhook_get($request->name)) )
            return redirect()->route('project.settings-sync', ['project' => $project, 'tab' => 'webhooks'])
                ->withError(trans('project.notifications.webhooks.error-create') . ': ' . trans('project.notifications.webhooks.error-exists'));

        //Добавления пустого поля 'fields', если в форме не было указано ни одного поля
        if(!$request->exists('fields'))
            $request->merge(['fields' => [] ]);

        $project->webhook_add($request->except(['_token']));
        $project->save();

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

        return redirect()->route('project.settings-sync', ['project' => $project, 'tab' => 'webhooks'])
            ->withSuccess( trans('project.notifications.webhooks.update-success') );
    } //update

    public function destroy(Project $project, string $webhook_name){
        //Проверка полномочий пользователя
        if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');
        
        $project->webhook_delete($webhook_name);
        $project->save();

        return redirect()->route('project.settings-sync', ['project' => $project, 'tab' => 'webhooks'])
                ->withSuccess( trans('project.notifications.webhooks.delete-success') );
    } //destroy

    public function test(){
        
    } //sendData

}
