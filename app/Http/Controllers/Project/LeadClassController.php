<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\Project\Project;
use App\Models\Project\LeadClass;
use App\Models\Leads;


class LeadClassController extends \App\Http\Controllers\Controller
{
    public function store(Request $request)
    {
        //Проверка полномочий пользователя
        // if(Gate::denies('settings', [Project::class, Project::find($request->project_id)]))
        //     return redirect()->route('project.index');

        //В данном контроллере можно добавлять только частные классы
        if($request->type !== LeadClass::TYPE_LOCAL)
            return back()->withError(trans('leads-classes.not-authorized'));


        //TODO Сделать отдельный запрос LeadsClassRequest с правилами валидации        
        if( //Проверка на наличие класса
            LeadClass::where([
                'name' => $request->name,
                'type' => LeadClass::TYPE_LOCAL,
                'color' => hexdec($request->color),
                'project_id' => $request->project_id,
            ])->exists()
        )
        return back()->withError(trans('leads-classes.create-error') . ': ' . trans('leads-classes.error-exists'));
        
        LeadClass::create([
            'name' => $request->name,
            'type' => LeadClass::TYPE_LOCAL,
            'color' => hexdec($request->color),
            'project_id' => $request->project_id,
        ]);

        return back()->withSuccess(trans('leads-classes.create-success'));
    } //store

    public function destroy(LeadClass $class)
    {
        //Проверка полномочий пользователя
        if(Gate::denies('settings', [Project::class, Project::find($request->project_id)]))
            return redirect()->route('project.index');

        //В данном контроллере можно удалять только частные классы
        if($request->type !== LeadClass::TYPE_LOCAL)
            return back()->withError(trans('leads-classes.not-authorized'));

        //Удаление класса из всех лидов в проекте
        Leads::where(['project_id' => $class->project_id, 'class' => $class->name])->update(['class' => null]);

        $class->delete();

        return back()->withSuccess(trans('leads-classes.delete-success'));
    } //destroy
}
