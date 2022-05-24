<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LeadsRequest;
use App\Http\Resources\Leads as LeadsResource;
use App\Models\Project\Host;
use App\Models\Leads;
use App\Models\User;
use App\Models\Project\Project;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;

//TODO Заменить на фасад
use App\Journal\Facade\Journal;

class LeadsController extends Controller
{
    public $leads;

    public function __construct(Leads $leads)
    {
        $this->leads = $leads;
    }

    public function store(LeadsRequest $request)
    {
        $request->merge(['project_id' => Project::where('api_token', $request->api_token)->value('id')]);

        if(filter_var($request->host, FILTER_VALIDATE_URL)){
            $host = parse_url($request->host);
            $request->merge(['host' => $host['host']]);
        }
        $request->merge(['host' =>  Str::lower($request->host)]);
        $phone = $request->phone;

        if ($phone[0] == 8) {
            $phone = preg_replace('/^./','7', $phone);
            $request->merge(['phone' => $phone]);
        }

        $request->merge(['cost' => preg_replace("/[^0-9]/", '', trim($request->cost))]);

        //Получение источника и UTM-меток
        $request->merge(['source' => $this->detectSource($request)]);
        $request->merge(['utm' => $this->getUTM($request)]);

        //Проверка хоста у лида
        if(!Host::where([ ['host', $request->host], ['project_id', $request->project_id] ])->exists()){
            Journal::leadError(['name' => $request->name, 'phone' => $request->phone, 'project_id' => $request->project_id ],
                        'Лид не добавлен в проект: хост ' . $request->host . ' не найден');
            return response()->json(['data' =>
                [
                    'status'  => Host::HOST_NOT_FOUND,
                    'message' => trans('leads.host-error'),
                    'response' => Response::HTTP_PRECONDITION_FAILED,
                ]
            ],Response::HTTP_PRECONDITION_FAILED);
        }

        if(!Project::findOrFail($request->project_id)->settings['enabled']) {
            Journal::leadWarning(['name' => $request->name, 'phone' => $request->phone, 'project_id' => $request->project_id ], "Попытка добавления лида в отключенный проект");
            return response()->json(['data' =>
                [
                    'status'  => Project::DISABLED,
                    'message' => trans('projects.enabled.false'),
                    'response' => Response::HTTP_FOUND,
                ]
            ],Response::HTTP_FOUND);
        }

        //Добавление владельца. Если владелец не авторизован, по умолчанию ставится "API"
        $user = User::where('api_token', $request->bearerToken())->first();
        $request->merge(['owner' => is_null($user) ? 'API' : $user->name]);

        //$new_lead = Leads::addToDB($request->all());
        $new_lead = $this->leads->createOrUpdate($request->all());

        Journal::lead($new_lead, $new_lead->entries == 1 ? 'Добавлен новый лид' : 'Лид уже существует в базе (кол-во вхождений: '  . $new_lead->entries . ')');

        return new LeadsResource(
            $new_lead
        );
    }

    public function detectSource(LeadsRequest $request){ //Определить источник
        //Если реферер не обнаружен, вернуть соответствующую запись
        if($request->exists('referrer') && ( parse_url($request->referrer,  PHP_URL_HOST) !== parse_url($request->host,  PHP_URL_HOST) ) ){
            return parse_url($request->referrer,  PHP_URL_HOST);
        }

        if(!$request->exists('url_query_string'))
            return Leads::SOURCE_DIRECT_ENTRY;

        $utm = [];
        parse_str(parse_url($request->url_query_string, PHP_URL_QUERY), $utm);

        if( array_key_exists('utm_source', $utm) )
            return $utm['utm_source'];
        else{
            Journal::leadWarning(['name' => $request->name, 'phone' => $request->phone, 'project_id' => $request->project_id ],
                                "Не удалось определить источник лида.");
            return Leads::SOURCE_DIRECT_ENTRY;
        }

    } //detectSource

    public function getUTM(LeadsRequest $request){ //Получить UTM-метки в виде массива
        $src = $request->exists('url_query_string')
                ? $request->url_query_string
                : ( $request->exists('referrer') ? $request->referrer : null);

        if(is_null($src))
            return [];

        $vars = [];
        parse_str(parse_url($request->url_query_string, PHP_URL_QUERY), $vars);

        $utm = [];

        foreach(['utm_source', 'utm_campaign', 'utm_medium', 'utm_term'] as $utm_mark){
            if( array_key_exists($utm_mark, $vars) )
                $utm[$utm_mark] = $vars[$utm_mark];
        }

        if(!count($utm))
            Journal::leadWarning(['name' => $request->name, 'phone' => $request->phone, 'project_id' => $request->project_id ],
                                    "Не удалось получить UTM-метки.");

        return $utm;

    } //getUTM

    public function update(LeadsRequest $request){
        //Проверка наличия лида
        $lead = Leads::find($request->id);
        if(is_null($lead))
            return response()->json(['error' => 'Lead not found'], Response::HTTP_NOT_FOUND);


        //Проверка полномочий
        // if(!Auth::guard('api')->check())
        //     return response()->json(['error' => 'You are not authorized for this action'], Response::HTTP_UNAUTHORIZED);
        // $user = Auth::guard('api')->user();
        $user = User::where('api_token', $request->bearerToken())->first();
        if(is_null($user))
            return response()->json(['error' => 'You are not authorized for this action'], Response::HTTP_UNAUTHORIZED);
        if($user->name !== $lead->owner){
            if(!$user->isAdmin())
                return response()->json(['error' => 'You are not owner of this lead'], Response::HTTP_FORBIDDEN);
        }

        //Изменение лида
         $lead_copy = clone $lead; //Копия лида для записи
         $lead->fill($request->all());
         $lead->owner = $user->name;
         $lead->save();

        Journal::lead($lead_copy, $user->name . ' изменил лид');
        return response()->json(['messsage' => 'Lead has been updated'], Response::HTTP_OK);
    } //update

    public function destroy(Request $request){
        //Валидация
        $request->validate(['id' => 'required|integer']);

        //Проверка наличия лида
        $lead = Leads::find($request->id);
        if(is_null($lead))
            return response()->json(['error' => 'Lead not found'], Response::HTTP_NOT_FOUND);

        //Проверка полномочий
        // if(!Auth::guard('api')->check())
        //     return response()->json(['error' => 'You are not authorized for this action'], Response::HTTP_UNAUTHORIZED);
        // $user = Auth::guard('api')->user();
        $user = User::where('api_token', $request->bearerToken())->first();
        if(is_null($user))
            return response()->json(['error' => 'You are not authorized for this action'], Response::HTTP_UNAUTHORIZED);
        if($user->name !== $lead->owner){
            if(!$user->isAdmin())
                return response()->json(['error' => 'You are not owner of this lead'], Response::HTTP_FORBIDDEN);
        }

        // $lead_info = ['id' => $lead->id, 'name' => $lead->getClientName(), 'phone' => $lead->phone];
        $lead_copy = clone $lead; //Копия лида для записи
        $lead->delete();

        // Journal::lead($lead_info, $user->name . ' удалил лид');
        Journal::lead($lead_copy, $user->name . ' удалил лид');

        return response()->json(['messsage' => 'Lead has been deleted'], Response::HTTP_OK);
    } //destroy
}
