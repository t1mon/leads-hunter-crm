<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LeadsRequest;
use App\Http\Resources\Leads as LeadsResource;
use App\Models\Project\Host;
use App\Models\Leads;
use App\Models\Project\Project;
use Illuminate\Http\Response;

use Illuminate\Support\Str;

//TODO Заменить на фасад
use App\Journal\Facade\Journal;

class LeadsController extends Controller
{
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

        //Получение источника и UTM-меток
        $request->merge(['source' => $this->detectSource($request)]);
        $request->merge(['utm' => $this->getUTM($request)]);

        //Проверка хоста у лида
        if(!Host::where([ ['host', $request->host], ['project_id', $request->project_id] ])->exists()){
            Journal::leadError(['name' => $request->name, 'phone' => $request->phone, 'project_id' => $request->project_id ], 
                        trans('leads.host-error') . ': ' . $request->host . ' (' . Host::HOST_NOT_FOUND  . ')');
            return response()->json(['data' =>
                [
                    'status'  => Host::HOST_NOT_FOUND,
                    'message' => trans('leads.host-error'),
                    'response' => Response::HTTP_PRECONDITION_FAILED,
                ]
            ],Response::HTTP_PRECONDITION_FAILED);
        }

        if(!Project::findOrFail($request->project_id)->settings['enabled']) {
            Journal::leadWarning(['name' => $request->name, 'phone' => $request->phone, 'project_id' => $request->project_id ], trans('projects.enabled.false'));
            return response()->json(['data' =>
                [
                    'status'  => Project::DISABLED,
                    'message' => trans('projects.enabled.false'),
                    'response' => Response::HTTP_FOUND,
                ]
            ],Response::HTTP_FOUND);
        }

        $new_lead = Leads::addToDB($request->all());
        
        Journal::lead($new_lead, $new_lead->entries == 1 ? Leads::LEAD_NEW : Leads::LEAD_EXISTS);

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
}
