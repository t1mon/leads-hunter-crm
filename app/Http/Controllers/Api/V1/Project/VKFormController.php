<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\LeadsController;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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
    const FIRST_NAME = 'first_name';
    const PATRONYMIC_NAME = 'patronymic_name';
    const LAST_NAME = 'last_name';
    const EMAIL = 'email';
    const PHONE_NUMBER = 'phone_number';
    const AGE = 'age';
    const BIRTHDAY = 'birthday';
    const LOCATION = 'location';
    const COMMENT = 'comment';
    const COST = 'cost';

    public $collection;
    public $leads;

    public function __construct(Leads $leads)
    {
        $this->leads = $leads;
        $this->collection = collect();
    }

    /*
    ##############
    CRUD-методы
    ##############
    */
    public function webHook(Project $project, Request $request){ //Подтверждение адреса и добавление лида
        $form = VKForm::where(['group_id' => $request->group_id, 'project_id' => $project->id])->first();

        //TODO Выяснить причину появления debugbar в теле запроса
        if (env('APP_DEBUG') === true ) app('debugbar')->disable();

        if($request->type === 'confirmation')
            return is_null($form) ? response(false, 404) : response($form->confirmation_response, 200);

        $this->setCollection($request['object']['answers']);
        //Взятие ключей из тела запроса
        $request->merge([
            'name' => $this->collection->pull(self::FIRST_NAME),
            'phone' => $this->collection->pull(self::PHONE_NUMBER),
            'project_id' => $project->id,
            'host' => $form->host,
            'source' => $form->source,
            'owner' => 'API (VK)',
            'comment' => $this->collection->pull(self::COMMENT) ?? '',
            'cost' => $this->collection->pull(self::COST) ?? '',
            'surname' => $this->collection->pull(self::LAST_NAME) ?? '',
            'patronymic' => $this->collection->pull(self::PATRONYMIC_NAME) ?? '',
            'email' => $this->collection->pull(self::EMAIL) ?? '',
            'city' => $this->collection->pull(self::LOCATION) ?? ''
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
    public function setCollection ($data)
    {
        foreach ($data as $d){

            switch ($d['key']) {
                case self::FIRST_NAME :
                    $this->collection->put(self::FIRST_NAME,$d['answer']);
                    break;
                case self::PATRONYMIC_NAME :
                    $this->collection->put(self::PATRONYMIC_NAME,$d['answer']);
                    break;
                case self::LAST_NAME :
                    $this->collection->put(self::LAST_NAME,$d['answer']);
                    break;
                case self::EMAIL :
                    $this->collection->put(self::EMAIL,$d['answer']);
                    break;
                case self::PHONE_NUMBER :
                    $this->collection->put(self::PHONE_NUMBER, preg_replace('![^0-9]+!', '',$d['answer']));
                    break;
                case self::AGE :
                    $this->collection->put(self::AGE,$d['answer']);
                    break;
                case self::BIRTHDAY :
                    $this->collection->put(self::BIRTHDAY,$d['answer']);
                    break;
                case self::LOCATION :
                    $this->collection->put(self::LOCATION,$d['answer']);
                    break;
                default:
                    if (strpos(mb_strtolower($d['question']),"сумма") !== false) {
                        $this->collection->put(self::COST,$d['answer']);
                    }
                    $comment = $this->collection->pull(self::COMMENT);
                    $this->collection->put(self::COMMENT,$comment." | ".$d['question']." : ".$d['answer']);
                    break;
            }


        }
    }

}
