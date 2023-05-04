<?php

namespace App\Console\Commands;

use App\Models\Leads;
use App\Models\Project\Project;
use Carbon\Carbon;
use Illuminate\Console\Command;

use \App\Repositories\Lead\Repository as LeadRepository;
use \App\Repositories\Lead\ReadRepository as LeadReadRepository;
use \App\Repositories\Project\Repository as ProjectRepository;
use \App\Repositories\Project\ReadRepository as ProjectReadRepository;

class FindRegionsInProject extends Command
{
    //
    //  Рабочие свойства
    //
    protected $signature = 'project:find_region {--project=all} {--date=} {--active} {--debug=0}';
    protected $description = 'Определить регион для лидов в проектах';

    protected LeadRepository $leadRepository;
    protected LeadReadRepository $leadReadRepository;
    protected ProjectRepository $projectRepository;
    protected ProjectReadRepository $projectReadRepository;
    
    //
    //  Аргументы команды
    //
    protected bool $debug;
    protected ?Project $project;
    protected Carbon $date;
    protected bool $active;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->leadRepository = app(abstract: LeadRepository::class);
        $this->leadReadRepository = app(abstract: LeadReadRepository::class);
        $this->projectRepository = app(abstract: ProjectRepository::class);
        $this->projectReadRepository = app(abstract: ProjectReadRepository::class);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Отладочная информация
        if($this->option('debug')){
            $this->info('Входные данные:');
            $this->info('debug: ' . $this->option('debug'));
            $this->info('project: ' . $this->option('project'));
            $this->info('date: ' . $this->option('date'));
            $this->info('active: ' . ($this->option('active') ? 'true' : 'false') );
        }

        //Если указан конкретный лид, выполнить поиск только для него

        //Загрузка аргументов
        if(!$this->_loadArguments())
            return 1;

        if(!$this->_findRegion())
            return 1;

        return 0;
    } //handle

    protected function _loadArguments(): bool //Загрузка аргументов
    {
        try{
            //Режим отладки
            $this->debug = $this->option('debug') ? true : false;

            //Проект
            $this->project = $this->option('project') === 'all' ? null :  $this->projectReadRepository->findById(id: $this->option('project'), fail: true);

            //Дата
            if(!empty($this->option('date')))
                $this->date = Carbon::parse(time: $this->option('date'), tz: 'UTC');

            $this->active = $this->option('active') ? true : false;
        }
        catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            $this->error("Проект с идентификатором {$this->option('project')} не найден");
            return false;
        }
        catch(\Carbon\Exceptions\InvalidFormatException){
            $this->error('Некорректный формат даты');
            return false;
        }

        return true;

    } //_loadArguments

    protected function _findRegion(): bool
    {
        $ids = is_null($this->project)
            ? $ids = $this->projectReadRepository
                ->query()
                ->when($this->active, function($query){
                    return $query->where('settings->enabled', false);
                })
                ->pluck(column: 'id', key: 'name')
            : [$this->project->name => $this->project->id];

        foreach($ids as $name => $id)
        {
            if($this->debug)
                $this->info("Проект #$id $name...");

            //Загрузка лидов
            $this->leadReadRepository->query()
                ->where('project_id', $id)
                ->whereNull('region')
                ->lazyById(chunkSize: 10, column: 'id')
                ->each(function($item){
                    if($this->debug)
                        $this->info("{$item->id}. {$item->full_name},  {$item->phone}");

                    $this->leadRepository->findRegion(lead: $item);
                });

        }

        return true;
    } //_findRegion
}
