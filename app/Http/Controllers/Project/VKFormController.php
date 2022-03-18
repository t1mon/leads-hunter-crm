<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\VKFormRequest;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\Leads;
use App\Models\Project\Project;
use App\Models\Project\VKForm;

class VKFormController extends Controller
{
    /*
    ##############
    CRUD-методы
    ##############
    */
    public function store(Project $project, VKFormRequest $request){
        $request->merge(['enabled' => $request->filled('enabled') ? true : false]);

        VKForm::create($request->all());
        return redirect()->route('project.integrations', $project)->withSuccess('Форма добавлена в базу данных');
    } //store

    public function edit(Project $project, VKForm $vk_form){
        return view('material-dashboard.project.integrations.vk.edit', ['project' => $project, 'form' => $vk_form]);
    } //edit

    public function update(Project $project, VKForm $vk_form, Request $request){

        $vk_form->fill($request->all());
        $vk_form->save();
        return redirect()->route('project.integrations', $project)->withSuccess('Изменения сохранены');
    } //edit

    public function destroy(Project $project, VKForm $vk_form){
        $vk_form->delete();
        return redirect()->route('project.integrations', $project)->withSuccess('Форма удалена из базы данных');
    } //destroy

    /*
    ##############
    Прочие методы
    ##############
    */
    public function toggle(Project $project, VKForm $vk_form){ //Включить/выключить форму
        $vk_form->enabled = !$vk_form->enabled;
        $vk_form->save();
        return redirect()->route('project.integrations', $project)
            ->withSuccess($vk_form->enabled ? 'Форма включена' : 'Форма выключена');
    } //toggle
}
