<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Role;
use App\Models\Project;
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
    protected $description = 'Создать разрешения пользователя для старых проектов';

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
            if(!UserPermissions::where(['user_id' => $project->user_id, 'project_id' => $project->id])->exists() ){
                
                UserPermissions::create([
                    'user_id' => $project->user_id,
                    'project_id' => $project->id,
                    'role_id' => Role::ROLE_ADMIN_ID,
                    'manage_users' => true,
                    'manage_settings' => true,
                    'manage_payments' => true,
                    'view_journal' => true,
                    'view_fields' => ['email', 'city', 'host'],
                ]);

            }
        }
    }
}
