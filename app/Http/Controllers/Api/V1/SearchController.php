<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

use App\Models\Role;
use App\Models\Leads;
use App\Models\User;
use App\Models\Project\Email;
use App\Models\Project\Host;
use App\Models\Project\LeadClass;
use App\Models\Project\Project;
use App\Models\Project\TelegramID;
use App\Models\Project\UserPermissions;
use App\Models\Project\Lead\Comment;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function __construct(){
        $this->models = collect([
            'Role' => Role::class,
            'Leads' => Leads::class,
            'User' => User::class,
            'Email' => Email::class,
            'Host' => Host::class,
            'LeadClass' => LeadClass::class,
            'Project' => Project::class,
            'TelegramID' => TelegramID::class,
            'UserPermissions' => UserPermissions::class,
            'Comment' => Comment::class,
        ]);
    } // __construct

    public function search(Request $request){
        //Валидация
        $request->validate([
            'value' => 'required',
        ]);

        $records = collect([]); //Найденные записи в базе данных (упаковываются в результаты)
        $results = []; //Результаты поисков

        //Поиск по моделям
        foreach($this->models as $model_key => $model){
            $all = $model::all(); //Список всех записей по данной модели
            if(is_null($all)) continue;
    
            $fields = collect($all[0]->toArray())->keys(); //Список доступных полей в модели

            //Поиск по каждому полю
            foreach($fields as $field){
                $temp = $all->filter(function($item) use ($field, $request){
                    return is_array($item->$field) ? false : (mb_stripos($item->$field, $request->value) !== false);
                    
                    // try{
                    //     return mb_stristr($item->$field, $request->value);
                    // }
                    // catch(\TypeError $e){
                    //     return false;
                    // }
                });

                if($temp->count())
                    $records->put($model_key, $temp);
            }
        }

        //Возврат результатов
        if($records->isNotEmpty()){
            $records = $records->unique(); //Удаление дубликатов

            //Упаковка данных
            foreach($records as $key => $data){
                $method = "_pack_$key";

                if(method_exists($this, $method)){ //Если указанный метод упаковки существует, вернуть упакованный результат
                    foreach($data as $item)
                        $results[$key][] = $this->$method($item);
                }
                elseif(Auth::guard('api')->user()->isAdmin()){ //Если метода нет, но пользователь является администратором, вернуть данные
                    foreach($data as $item)
                        $results[$key][] = $item;
                }
            }

            return response()->json(['data' => $results], Response::HTTP_OK);
        }
        else
            return response()->json(['message' => 'Результатов по запросу не найдено'], Response::HTTP_OK);
        
    } //search

    /*###############
    Методы-упаковщики
    ################*/
    protected function _pack_Leads(Leads $lead){
        return [
            'title' => "Лид №{$lead->id} ({$lead->getClientName()}, {$lead->phone}), проект \"{$lead->project->name}\"",
            'link' => route('project.journal', $lead->project->id),
        ];
    } //_pack_Leads

    protected function _pack_Project(Project $project){
        return [
                'title' => $project->name,
                'link' => route('project.journal', $project->id),
            ];
    } //_pack_Project
}
