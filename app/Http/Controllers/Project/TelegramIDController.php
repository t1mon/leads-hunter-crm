<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use App\Models\Project\Project;
use App\Models\Project\TelegramID;

class TelegramIDController extends Controller
{    
    public function store(Project $project, Request $request){
        //Проверка полномочий пользователя
        if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index')->withError(trans('projects.not_authorized'));
        
        //Если групповой чат уже есть, он заменяется новым (тогда не нужно писать метод update)
        if($request->type === TelegramID::TYPE_GROUP){
            TelegramID::updateOrCreate(
                ['project_id' => $project->id, 'type' => TelegramID::TYPE_GROUP],
                ['name' => $request->name]
            );

            return redirect()->route('project.settings-sync', [$project, 'telegram'])
                ->withSuccess( trans('projects.notifications.telegram.group_create_success') );
        }
        
        if($request->type === TelegramID::TYPE_PRIVATE){
            if( TelegramID::where(['project_id' => $project->id, 'name' => $request->name])->exists() )
                return redirect()->route('project.settings-sync', [$project, 'telegram'])
                    ->withError( trans('projects.notifications.telegram.create_error') . ': ' . trans('projects.notifications.telegram.error_exists') );
            
            TelegramID::create(['project_id' => $project->id, 'name' => $request->name, 'type' => $request->type]);

            return redirect()->route('project.settings-sync', [$project, 'telegram'])
                ->withSuccess( trans('projects.notifications.telegram.private_create_success') );
        }

    } //store

    public function destroy(Project $project, $id){
        //Проверка полномочий пользователя
        if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index')->withError(trans('projects.not_authorized'));
        
        $contact = TelegramID::find($id);
        
        $contact->delete();
        return redirect()->route('project.settings-sync', [$project, 'telegram'])
                ->withSuccess( trans('projects.notifications.telegram.delete_success') );
    } //deletee
}
