<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Repositories\Project\ReadRepository as ProjectRepository;
use App\Repositories\Lead\Repository as LeadRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SplitUTM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:split_utm {--project=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Распределить метки из json-поля utm по отдельным полям';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private ProjectRepository $projectRepository,
        private LeadRepository $leadsRepository 
    )
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
        if($this->option('project') === 'all') //Выполнить для всех проектов
        {
            $this->projectRepository->query()
                ->chunkById(100, function($projects){
                    foreach($projects as $project){
                        $this->info("Проект #{$project->id}: {$project->name}...");
                        $this->leadsRepository->splitUTMForProject($project);
                        $this->info("Проект #{$project->id}: {$project->name} обработан");
                    }
                });
        }
        else{
            try{
                $project = $this->projectRepository->findById(id: $this->option('project'), fail: true);
                $this->info("Проект #{$project->id}: {$project->name}...");
                $this->leadsRepository->splitUTMForProject($project, $this->option('all'));
                $this->info("Проект #{$project->id}: {$project->name} обработан");
            }
            catch(ModelNotFoundException $e)
            {
                $this->error('Проект с таким идентификатором не найден:');
                $this->error($e->getMessage());
                return 1;
            }
        }

        $this->info('Команда выполнена');
        
        return 0;
    }
}
