<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\Controller;

use App\Models\Project\Project;
use App\Models\Project\LeadClass;
use App\Models\Leads;


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
                'color' => $request->color,
                'project_id' => $project->id,
            ])->exists()
        )
        return back()->withError(trans('leads-classes.create-error') . ': ' . trans('leads-classes.error-exists'));
        
        LeadClass::create([
            'name' => $request->name,
            'type' => LeadClass::TYPE_LOCAL,
            'color' => $request->color,
            'project_id' => $project->id,
        ]);

        return redirect()->route('project.settings-basic', [$project, 'tab' => 'classes'])->withSuccess(trans('leads-classes.create-success'));
    } //store

    public function edit(Project $project, LeadClass $class){
        return view('material-dashboard.project.class-edit', compact('project', 'class'));
    } //edit

    public function update(Project $project, LeadClass $class, Request $request){
        $class->fill($request->all());
        $class->save();
        return redirect()->route('project.settings-basic', [$project, 'tab' => 'classes'])->withSuccess(trans('leads-classes.create-success')); 
    } //update

    public function destroy(Project $project, LeadClass $class){
        //Проверка полномочий пользователя
        if(Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index');

        //Удаление класса из всех лидов в проекте
        Leads::where(['project_id' => $project->id, 'class' => $class->name])->update(['class' => null]);

        $class->delete();

        return back()->withSuccess(trans('leads-classes.delete-success'));
    } //destroy
}
