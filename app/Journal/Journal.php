<?php

namespace App\Journal;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use App\Models\Project\Project;
use App\Models\Leads;

class Journal{
    /*  1.
    ############
        Свойства
    #############
    */
    
    const TABLE = 'journal'; //Имя таблицы
    const FILEPATH = 'storage/journal/'; //Пусть для сохранения в файл

    //Классы записей
    const CLASS_INFO = 'info';
    const CLASS_WARNING = 'warning';
    const CLASS_ERROR = 'error';

    //Типы действий
    const ACTION_LOG = 'log'; //Обыкновенная запись в лог
    const ACTION_PROJECT = 'project'; //Запись по проекту
    const ACTION_LEAD = 'lead'; //Запись по лиду

    /* 2.
    ############
        Методы
    #############
    */

    public function _toCollection($rawdata){ //Служебный метод для преобразования данных из БД в коллекцию
        return collect($rawdata)->map(function($element){
            return json_decode($element->data);
        });
    } //_convert

    public function recent(int $amount = 50){ //Получить все последние записи в логе (по умолчанию последние 10)        
        return $this->_toCollection(DB::table(self::TABLE)->orderBy('date', 'desc')->limit($amount)->get());
    } //recent

    public function allInProject(Project $project){ //Получить все имеющиеся записи по конкретному проекту
        return $this->_toCollection(DB::table(self::TABLE)->where('data->project->id', $project->id)->orderBy('date', 'desc')->get());
    }

    public function recentInProject(Project $project, int $amount = 10){ //Последние записи в логе по конкретному проекту
        return $this->_toCollection(DB::table(self::TABLE)->where('data->project->id', $project->id)
                    ->orderBy('date', 'desc')->limit($amount)->get());
    } //recentInProject

    public function write(string $class, string $action, string $text, array $optional = []){ //Базовая функции записи
        //Базовые поля
        $entry = [
            'date' => Carbon::now(),
            'class' => $class,
            'action' => $action,
            'text' => $text,
        ];

        //Дополнительные поля
        if(Auth::check())
            $entry['user'] = ['name' => Auth::user()->name, 'id' => Auth::id()];

        foreach($optional as $key => $value)
            $entry[$key] = $value;

        DB::table($this->TABLE)->insert(['data' => json_encode($entry), 'date' => Carbon::now()]);
    } //write




    /* 2.1
                ############
                Простые записи
                #############
    */
    public function info(string $text){ //Простая информационная запись
        // $this->write($this->CLASS_INFO, $this->ACTION_LOG, $this->ACTION_LOG, $text);
        $this->write($this->CLASS_INFO, $this->ACTION_LOG, $this->ACTION_LOG, $text);
    } //info

    public function warning(string $text){ //Запись о предупреждении
        // $this->write($this->CLASS_WARNING, $this->ACTION_LOG, $this->ACTION_LOG, $text);
        $this->write($this->CLASS_WARNING, $this->ACTION_LOG, $this->ACTION_LOG, $text);
    } //warning

    public function error(string $text){ //Запись об ошибке
        // $this->write($this->CLASS_ERROR, $this->ACTION_LOG, $this->ACTION_LOG, $text);
        $this->write($this->CLASS_ERROR, $this->ACTION_LOG, $this->ACTION_LOG, $text);
    } //error



    /* 2.2
                ############
                Записи по проектам
                #############
    */
    public function projectWrite(Project $project, string $class, string $text){ //Метод для удобного изменения
        $this->write($class, $this->ACTION_PROJECT, $text, 
                    [ 'project' => ['id' => $project->id, 'name' => $project->name] ]);
    } //projectWrite

    public function project(Project $project, string $text){ //Простая информационная запись по проекту
        $this->projectWrite($project, $this->CLASS_INFO, $text);
    } //project

    public function projectWarning(Project $project, string $text){ //Предупреждение по проекту
        $this->projectWrite($project, $this->CLASS_WARNING, $text);
    } //projectWarning

    public function projectError(Project $project, string $text){ //Ошибка по проекту        
        $this->projectWrite($project, $this->CLASS_ERROR, $text);
    } //projectError



    /* 2.3
                ############
                Записи по лидам
                #############
    */
    public function leadWrite($lead, string $class, string $text){ //Метод для удобного изменения
        $params = $lead instanceof Leads
                    ? [
                        'lead' => ['id' => $lead->id, 'name' => $lead->name, 'phone' => $lead->phone],
                        'project' => ['id' => $lead->project->id, 'name' => $lead->project->name] 
                    ]
                    : [
                        'lead' => ['name' => $lead['name'], 'phone' => $lead['phone'] ],
                        'project' => ['id' => $lead['project_id'], 'name' => Project::find($lead['project_id']) ]
                    ];
        
        $this->write($class, $this->ACTION_LEAD, $text, $params);
    } //leadWrite

    public function lead($lead, string $text){ //Простая информационная запись по лиду
        $this->leadWrite($lead, $this->CLASS_INFO, $text);
    } //lead

    public function leadWarning($lead, string $text){ //Предупреждение по лиду
        $this->leadWrite($lead, $this->CLASS_WARNING, $text);
    } //leadWarning

    public function leadError($lead, string $text){ //Ошибка по лиду
        $this->leadWrite($lead, $this->CLASS_ERROR, $text);
    } //leadWarning
};

?>