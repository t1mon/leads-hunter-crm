<?php

namespace App\Http\Controllers\Api\V1\Project\Lead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

use App\Models\Project\Project;
use App\Models\Project\LeadClass;
use App\Models\Leads;
use Illuminate\Support\Facades\Auth;

use App\Journal\Facade\Journal;

class LeadClassController extends Controller
{
    public function store(Project $project, Request $request){
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);
        
        //Валидация
        $request->validate([
            'name' => 'required',
            'color' => 'required',
        ]);

        //Проверка на наличие класса
        if(LeadClass::where([
            'name' => $request->name,
            'type' => LeadClass::TYPE_LOCAL,
            'project_id' => $project->id,
        ])->exists()){
            Journal::projectError($project, trans('leads-classes.create-error') . ': ' . $request->name . ' – ' . trans('leads-classes.error-exists'));
            return response()->json(['error' => 'Class already exists'], Response::HTTP_PRECONDITION_FAILED);  
        }

        LeadClass::create([
            'name' => $request->name,
            'type' => LeadClass::TYPE_LOCAL,
            'color' => $request->color,
            'project_id' => $project->id,
        ]);

        Journal::project($project, $user->name . ' добавил класс "' . $request->name . '".');
        return response()->json(['message' => 'Class has been created'], Response::HTTP_CREATED);
    } //store

    public function update(Project $project, LeadClass $class, Request $request){
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);

        //Валидация
        $request->validate([
            'name' => 'required',
            'color' => 'required',
        ]);

        $class->fill($request->all());
        $class->save();

        Journal::project($project, $user->name . ' обновил класс "' . $class->name . '".');
        return response()->json(['message' => 'Class has been updated'], Response::HTTP_OK);
    } //update

    public function destroy(Project $project, LeadClass $class){
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);
        
        //Удаление класса из всех лидов в проекте
        Leads::where(['project_id' => $project->id, 'class_id' => $class->id])->update(['class_id' => null]);

        $name = $class->name;
        $class->delete();

        Journal::project($project, $user->name . ' удалил класс "' . $name . '".');
        return response()->json(['message' => 'Class has been deleted'], Response::HTTP_OK);
    } //destroy

    public function assign(Project $project, Leads $lead, Request $request){ //Назначить класс лиду
        $user = Auth::guard('api')->user();

        Leads::where('id', $lead->id)->update([
            'class_id' => $request->class_id ? $request->class_id : null, 
            'updated_at' => $lead->updated_at
        ]);

        if($request->class_id)
            Journal::project($project,
                        $user->name . ' назначил класс "' . LeadClass::find($request->class_id)->name . '" лиду №' . $lead->id . '(' . $lead->name . ', ' . $lead->phone . ').');
        else
        Journal::lead($lead, $user->name . ' убрал класс с лида');
        return response()->json(['message' => 'Class has been assigned'], Response::HTTP_OK);
    } //assign
}
