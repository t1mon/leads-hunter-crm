<?php

namespace App\Console\Commands;

use App\Models\Project\Project;
use Illuminate\Console\Command;

use App\Repositories\Project\ReadRepository as ProjectRepository;

class CountLeadsInProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:count_leads {--project=all} {--leads=both} {--debug=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Посчитать лиды в существующих проектах';

    private string $_project;
    private string $_leads;
    private bool $_debug;
    private float $_totalTime = 0;
    private float $_timePerProject;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Загрузка данных
        $this->_load();

        //Валидация данных
        if(!$this->_validate())
            return 1;

        //Подсчёт лидов
        $this->_count();
        $this->_debug_output();

        // $starttime = microtime(true);

        // // $total = ProjectRepository::countTotalLeads($this->option('project'));
        // $total = ProjectRepository::countLeadsToday($this->option('project'));
        // $load = sys_getloadavg()[0];
        // $memory = memory_get_usage();
        // $endtime = microtime(true);
        // $timediff = $endtime - $starttime;

        // $this->info('Всего лидов: ' . $total);
        // $this->info('Времени потрачено: ' . $timediff);
        // $this->info('Память: ' . $memory);
        // $this->info('Нагрузка: ' . $load);

        return 0;
    }

    //
    //  Служебные методы
    //
    private function _load(): void
    {
        $this->_project = $this->option('project');
        $this->_leads = $this->option('leads');

        if( in_array(needle: $this->option('debug'), haystack: ['true', 'false', 0, 1]) )
            $this->_debug = $this->option('debug');
        else
            $this->warn('Опция debug может иметь значение true, false, 0 или 1. Другие значения могут считываться некорректно');
    } //_load

    private function _validate(): bool
    {
        $success = true;

        //Проверка поля _project
        if( !is_numeric($this->_project) && $this->_project !== 'all' ){
            $this->error('Опция project может указываться либо как одно число, либо как all');
            $success = false;
        }

        //Проверка поля _leads
        if( !in_array(needle: $this->_leads, haystack: ['total', 'today', 'both']) ){
            $this->error('Опция leads может иметь значение total, today или both');
            $success = false;
        }

        return $success;
    } //_validate

    private function _count(): void //Общая функция для подсчёта
    {
        $method = '_count_'.$this->_leads; //Метод, который будет использоваться для подсчёта

        if(is_numeric($this->_project)){
            $this->_debug_start();
            $this->$method($this->_project);
            $this->_debug_stop($this->_project);
        }
        elseif($this->_project === 'all'){
            $ids = Project::pluck('id');

            foreach($ids as $id){
                $this->_debug_start();
                $this->$method($id);
                $this->_debug_stop($id);
            }
        }
    } //_count

    private function _count_total(int $project_id): void
    { //Подсчёт общего количества лидов
        ProjectRepository::countTotalLeads(project_id: $project_id);
    } //_count_total

    private function _count_today(int $project_id): void
    { //Подсчёт лидов за сегодня
        ProjectRepository::countLeadsToday(project_id: $project_id);
    } //_count_today

    private function _count_both(int $project_id): void
    { //Подсчёт общего количества лидов и лидов за сегодня
        ProjectRepository::countTotalLeads(project_id: $project_id);
        ProjectRepository::countLeadsToday(project_id: $project_id);
    } //_count_today

    private function _debug_start(): void
    {
        if($this->_debug)
            $this->_timePerProject = microtime(true);
    } //_debug_start

    private function _debug_stop(int $project): void
    {
        if($this->_debug){
            $timeDiff = microtime(true) - $this->_timePerProject;
            $this->_totalTime += $timeDiff;
            $this->info('Потрачено времени на проект #' . $project . ': ' . $timeDiff);
        }
    } //_debug_stop

    private function _debug_output(): void
    {
        if($this->_debug)
            $this->info('Потрачено времени на все проекты: ' . $this->_totalTime);
    } //_debug_output
}
