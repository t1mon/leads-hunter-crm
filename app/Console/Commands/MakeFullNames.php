<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Repositories\Project\ReadRepository as ProjectRepository;
use App\Repositories\Lead\Repository as LeadRepository;

class MakeFullNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:make_names {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Заполнить поле full_name в проектах';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private ProjectRepository $projectRepository,
        private LeadRepository $leadsRepository)
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
        $this->projectRepository->query()
            ->chunkById(100, function($projects){
                $projects->each(function($project){
                    $this->info("Проект #{$project->id}: {$project->name}...");
                    $this->leadsRepository->makeFullNamesForProject($project, $this->option('all'));
                    $this->info("Проект #{$project->id}: {$project->name} обработан");
                });

                // foreach($projects as $project){
                //     $this->info("Проект #{$project->id}: {$project->name}...");
                //     $this->leadsRepository->makeFullNamesForProject($project, $this->option('all'));
                //     $this->info("Проект #{$project->id}: {$project->name} обработан");
                // }
            });

        $this->info('Команда выполнена');
        return 0;
    }
}
