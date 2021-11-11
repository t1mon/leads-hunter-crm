<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Role;
use App\Models\Project\Project;
use App\Models\Project\UserPermissions;

class MigrateProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновить старые проекты под новые функции';

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
        $projects = Project::all();

        foreach($projects as $project){
            /* 1.
                Создать полномочия для создателя в каждом проекте */
            if(!UserPermissions::where(['user_id' => $project->user_id, 'project_id' => $project->id])->exists() ){              
                
                UserPermissions::create([
                    'user_id' => $project->user_id,
                    'project_id' => $project->id,
                    'role' => Role::ROLE_MANAGER,
                    'view_fields' => ['email', 'city', 'host'],
                ]);

            }

            /* 2.
                Добавить настройки рассылки Telegram по умолчанию */
            if(!array_key_exists('telegram', $project->settings)){
                $new_settings = [
                    'telegram' =>
                    [
                        'enabled' => true,
                        'fields' => [],
                    ]
                ];

                $project->settings = array_merge($project->settings, $new_settings);

                $project->save();
            }
        }
    }
}
