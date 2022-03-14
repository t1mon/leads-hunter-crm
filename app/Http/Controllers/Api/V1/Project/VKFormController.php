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
    public function leadAdd(Project $project, Request $request){ //Подтверждение адреса и добавление лида
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
        
        //https://ru.stackoverflow.com/questions/646326/vk-callback-api-%D0%BD%D0%B0-%D0%BE%D0%B4%D0%B8%D0%BD-%D0%B7%D0%B0%D0%BF%D1%80%D0%BE%D1%81-%D0%BC%D0%BD%D0%BE%D0%B3%D0%BE-%D0%BE%D1%82%D0%B2%D0%B5%D1%82%D0%BE%D0%B2
        
        $this->leads->createOrUpdate($request->all());

        Log::info($request->all());

        return 200;
    } //store

    /*
    ##############
    Прочие методы
    ##############
    */
}
