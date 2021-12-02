<?php

namespace App\Journal;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Project\Project;
use App\Models\Project\Leads;

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

    static public function recent(int $amount = 10){ //Получить последние записи в логе (по умолчанию последние 10)
        return DB::table(self::TABLE)->orderBy('date', 'desc')->limit($amount)->get();
    } //recent

    static public function write(string $class, string $action, string $text, array $optional = []){ //Базовая функции записи
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

        DB::table(self::TABLE)->insert(['data' => json_encode($entry), 'date' => Carbon::now()]);
    } //write




    /* 2.1
                ############
                Простые записи
                #############
    */
    static public function info(string $text){ //Простая информационная запись
        // $this->write(self::CLASS_INFO, self::ACTION_LOG, self::ACTION_LOG, $text);
        self::write(self::CLASS_INFO, self::ACTION_LOG, self::ACTION_LOG, $text);
    } //info

    static public function warning(string $text){ //Запись о предупреждении
        // $this->write(self::CLASS_WARNING, self::ACTION_LOG, self::ACTION_LOG, $text);
        self::write(self::CLASS_WARNING, self::ACTION_LOG, self::ACTION_LOG, $text);
    } //warning

    static public function error(string $text){ //Запись об ошибке
        // $this->write(self::CLASS_ERROR, self::ACTION_LOG, self::ACTION_LOG, $text);
        self::write(self::CLASS_ERROR, self::ACTION_LOG, self::ACTION_LOG, $text);
    } //error



    /* 2.2
                ############
                Записи по проектам
                #############
    */
    static public function projectWrite(Project $project, string $class, string $text){ //Метод для удобного изменения
        self::write($class, self::ACTION_PROJECT, $text, 
                    [ 'project' => ['id' => $project->id, 'name' => $project->name] ]);
    } //projectWrite

    static public function project(Project $project, string $text){ //Простая информационная запись по проекту
        self::projectWrite($project, self::CLASS_INFO, $text);
    } //project

    static public function projectWarning(Project $project, string $text){ //Предупреждение по проекту
        self::projectWrite($project, self::CLASS_WARNING, $text);
    } //projectWarning

    static public function projectError(Project $project, string $text){ //Предупреждение по проекту        
        self::projectWrite($project, self::CLASS_ERROR, $text);
    } //projectError



    /* 2.3
                ############
                Записи по лидам
                #############
    */
    static public function leadWrite(Leads $lead, string $class, string $text){ //Метод для удобного изменения
        self::write($class, self::ACTION_LEAD, $text, 
                    [
                        'lead' => ['id' => $lead->id, 'name' => $lead->name, 'phone' => $lead->phone],
                        'project' => ['id' => $lead->project->id, 'name' => $lead->project->name] 
                    ]);
    } //leadWrite

    static public function lead(Leads $lead, string $text){ //Простая информационная запись по лиду
        self::leadWrite($lead, self::CLASS_INFO, $text);
    } //lead

    static public function leadWarning(Leads $lead, string $text){ //Предупреждение по лиду
        self::leadWrite($lead, self::CLASS_WARNING, $text);
    } //leadWarning

    static public function leadError(Leads $lead, string $text){ //Ошибка по лиду
        self::leadWrite($lead, self::CLASS_ERROR, $text);
    } //leadWarning
};

?>