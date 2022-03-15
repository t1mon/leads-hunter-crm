<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\LeadsController;
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
    public $leads;

    public function __construct(Leads $leads)
    {
        $this->leads = $leads;
    }

    /*
    ##############
    CRUD-методы
    ##############
    */
    public function webHook(Project $project, Request $request){ //Подтверждение адреса и добавление лида
        $form = VKForm::where(['group_id' => $request->group_id, 'project_id' => $project->id])->first();

        //TODO Выяснить причину появления debugbar в теле запроса
        app('debugbar')->disable();

        if($request->type === 'confirmation')
            return is_null($form) ? response(false, 404) : response($form->confirmation_response, 200);

        //Взятие ключей из тела запроса
        $request->merge([
            'name' => $request->object['answers'][0]['answer'],
            'phone' => preg_replace('![^0-9]+!', '', $request->object['answers'][1]['answer']),
            'project_id' => $project->id,
            'host' => $form->host,
            'source' => $form->source,
            'owner' => 'API (VK)',
        ]);

        $this->leads->createOrUpdate($request->all());

        Log::info($request->all());

        return response('ok', 200);
    } //store

    /*
    ##############
    Прочие методы
    ##############
    */
}
