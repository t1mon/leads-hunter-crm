<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\Controller;

use App\Models\Project\Project;
use App\Models\Project\LeadClass;
use App\Models\Leads;

use Illuminate\Support\Facades\Auth;

use App\Journal\Facade\Journal;

class LeadClassController extends Controller
{
    public function index(Project $project){

    } //index

    public function create(Project $project){

    } //create

    public function store(Project $project, Request $request){
        //Проверка полномочий пользователя
        if(Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');

        //TODO Сделать отдельный запрос LeadsClassRequest с правилами валидации
        if( //Проверка на наличие класса
            LeadClass::where([
                'name' => $request->name,
                'type' => LeadClass::TYPE_LOCAL,
                'project_id' => $project->id,
            ])->exists()
        ){
            Journal::projectError($project,
                                                trans('leads-classes.create-error') . ': ' . $request->name . ' – ' . trans('leads-classes.error-exists'));
            return back()->withError(trans('leads-classes.create-error') . ': ' . trans('leads-classes.error-exists'));
        }

        LeadClass::create([
            'name' => $request->name,
            'type' => LeadClass::TYPE_LOCAL,
            'color' => $request->color,
            'project_id' => $project->id,
        ]);

        Journal::project($project, Auth::user()->name . ' добавил класс "' . $request->name . '".');
        return redirect()->route('project.settings-basic', [$project, 'tab' => 'classes'])->withSuccess(trans('leads-classes.create-success'));
    } //store

    public function edit(Project $project, LeadClass $class){
        //Проверка полномочий пользователя
        if(Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');

        return view('material-dashboard.project.class-edit', compact('project', 'class'));
    } //edit

    public function update(Project $project, LeadClass $class, Request $request){
        //Проверка полномочий пользователя
        if(Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');

        $class->fill($request->all());
        $class->save();

        Journal::project($project, Auth::user()->name . ' обновил класс "' . $class->name . '".');
        return redirect()->route('project.settings-basic', [$project, 'tab' => 'classes'])->withSuccess(trans('leads-classes.create-success'));
    } //update

    public function destroy(Project $project, LeadClass $class){
        //Проверка полномочий пользователя
        if(Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');

        //Удаление класса из всех лидов в проекте
        Leads::where(['project_id' => $project->id, 'class_id' => $class->id])->update(['class_id' => null]);

        $name = $class->name;
        $class->delete();

        Journal::project($project, Auth::user()->name . ' удалил класс "' . $name . '".');

        return back()->withSuccess(trans('leads-classes.delete-success'));
    } //destroy

    public function assign(Project $project, Leads $lead, Request $request){ //Назначить класс лиду
        Leads::where('id', $lead->id)->update([
            'class_id' => $request->class_id ? $request->class_id : null,
            'updated_at' => $lead->updated_at
        ]);

        if($request->class_id)
            Journal::project($project,
                        Auth::user()->name . ' назначил класс "' . LeadClass::find($request->class_id)->name . '" лиду №' . $lead->id . ' (' . $lead->name . ', ' . $lead->phone . ').');
        else
        Journal::lead($lead, Auth::user()->name . ' убрал класс с лида');
        return redirect()->route('project.journal', $project);
    } //assign
}
