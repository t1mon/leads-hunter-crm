<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Project\Project;
use App\Repositories\Project\Repository as ProjectRepository;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Models\Leads;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use Carbon\Carbon;

class ArchiveProjects extends Command
{

    private int $archived = 0; //Количество проектов, отправленных в архив

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:archive {--project=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Переместить проект в архив';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectReadRepository $projectReadRepository,
        private LeadReadRepository $leadReadRepository,
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
        if($this->option('project') === 'all')
            $this->checkAllProjects();
            if($this->archived > 0)
                $this->warn("В архив перемещено {$this->archived} проектов.");
        else{
            $project = $this->projectReadRepository->findById($this->option('project'), fail: true);
            $this->info("Проект #{$project->id} {$project->name}...");
            $this->checkProject($project);
        }

        $this->info('Команда выполнена');
        return 0;
    }

    private function checkAllProjects()
    {
        $this->projectReadRepository->query()
            ->chunkById(100, function($projects){
                $projects->each(function($project){
                    $this->info("Проект #{$project->id} {$project->name}...");
                    $this->checkProject($project);
                });
            });
    } //allProjects

    private function checkProject(Project $project)
    {
        $lead = $this->leadReadRepository->query()->from($project)->latest()->first();
        if(!is_null($lead) && Carbon::now()->diffInMonths($lead->created_at) > 0){
            $this->projectRepository->disable($project);
            $this->archived++;
            $this->warn("Проект #{$project->id} {$project->name} отключен");
        }
    } //checkProject
}
