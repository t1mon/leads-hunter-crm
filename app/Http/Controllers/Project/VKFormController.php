<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    public function store(Project $project, Request $request){
        $request->merge(['project_id' => $project->id, 'enabled' => $request->filled('enabled') ? true : false]);

        VKForm::create($request->all());
        return redirect()->route('project.integrations', $project);
    } //store

    public function destroy(Project $project, VKForm $vk_form){
        $vk_form->delete();
        return redirect()->route('project.integrations', $project);
    } //destroy
}