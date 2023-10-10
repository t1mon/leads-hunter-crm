<?php

namespace App\Journal;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use App\Models\Project\Project;
use App\Models\Leads;
use App\Models\Log;

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

    public function recent(int $amount = 50){ //Получить все последние записи в логе (по умолчанию последние 50)
        return Log::query()->latest()->limit($amount)->get();
    } //recent

    public function allInProject(Project $project, ?int $type = null){ //Получить все имеющиеся записи по конкретному проекту
        return Log::query()->project($project, $type)->latest()->simplePaginate(50);
    }

    public function recentInProject(Project $project, int $amount = 10){ //Последние записи в логе по конкретному проекту
        return Log::query()->project($project)->latest()->limit($amount)->get();
    } //recentInProject

    public function todayInProject(Project $project){ //Получить все записи за сегодня
        $date = Carbon::today($project->timezone)->setTimezone(config('app.timezone'));
        return Log::query()->project($project)->where('created_at', '>=', $date)->simplePaginate(50);
    } //todayInProject

    public function write(int $type, Project|int $project, string $text){ //Базовая функции записи
        Log::create([
            'type' => $type,
            'project_id' => $project instanceof Project ? $project->id : $project,
            'text' => $text,
        ]);
    } //write

    //
    // ЗАПИСИ ПО ПРОЕКТАМ
    //
    public function project(Project|int $project, string $text){ //Простая информационная запись по проекту
        $this->write(type: Log::TYPE_INFO, project: $project, text: $text);
    } //project

    public function projectWarning(Project|int $project, string $text){ //Предупреждение по проекту
        $this->write(type: Log::TYPE_WARNING, project: $project, text: $text);
    } //projectWarning

    public function projectError(Project|int $project, string $text){ //Ошибка по проекту        
        $this->write(type: Log::TYPE_ERROR, project: $project, text: $text);
    } //projectError



    //
    //  ЗАПИСИ ПО ЛИДАМ
    //
    public function leadWrite($lead, int $type, string $text){ //Метод для удобного изменения
        $text .= ' [Лид #' . $lead->id . ', телефон ' . $lead->phone . ']';
        $this->write(type: $type, project: $lead->project_id, text: $text);
    } //leadWrite

    public function lead($lead, string $text){ //Простая информационная запись по лиду
        $this->leadWrite(lead: $lead, type: Log::TYPE_INFO, text: $text);
    } //lead

    public function leadWarning($lead, string $text){ //Предупреждение по лиду
        $this->leadWrite(lead: $lead, type: Log::TYPE_WARNING, text: $text);
    } //leadWarning

    public function leadError($lead, string $text){ //Ошибка по лиду
        $this->leadWrite(lead: $lead, type: Log::TYPE_ERROR, text: $text);
    } //leadWarning
};

?>