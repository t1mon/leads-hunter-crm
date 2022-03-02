<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;


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
                Добавить настройки рассылки e-mail по умолчанию */
            if(!array_key_exists('email', $project->settings)){
                $new_settings = [
                    'email' =>
                    [
                        'enabled' => false,
                        'send_all' => true,
                        'subject' => $project->name,
                        'fields' => [],
                    ]
                ];

                $project->settings = array_merge($project->settings, $new_settings);
            }    

            /* 3.
                Добавить тему e-mail по умолчанию */
            if(!array_key_exists('subject', $project->settings['email'])){
                $new_settings = $project->settings;
                $new_settings['email']['subject'] = $project->name;
                $project->settings = $new_settings;
            }

            /* 4.
                Убрать рассылку всех лидов на e-mail по умолчанию */
                if(array_key_exists('send_all', $project->settings['email'])){
                    $new_settings = $project->settings;
                    unset($new_settings['email']['send_all']);
                    $project->settings = $new_settings;
                }

            /* 5.
                Добавить настройки рассылки Telegram по умолчанию */
            if(!array_key_exists('telegram', $project->settings)){
                $new_settings = [
                    'telegram' =>
                    [
                        'enabled' => false,
                        'fields' => [],
                    ]
                ];

                $project->settings = array_merge($project->settings, $new_settings);

            }
            /* 6.
                Добавить в настройки проекта часовой пояс (по умолчанию UTC)*/
            if(!array_key_exists('timezone', $project->settings)){
                $new_settings = [ 'timezone' => 'Europe/Moscow'];
                $project->settings = array_merge($project->settings, $new_settings);
            }

            /* 7.
                Перевести дату всех созданных проектов из Москвы в UTC*/
            $project->created_at = Carbon::create($project->created_at)->timezone('UTC');
            
            /* 8.
                Перевести даты всех лидов в проекте из Москвы в UTC*/
            foreach($project->leads() as $lead){
                $lead->created_at = Carbon::create($lead->created_at)->timezone('UTC');
                $lead->save();
            }
            
            /* 9.
                Добавить в настройки вебхуки*/
            if(!array_key_exists('webhooks', $project->settings)){
                $new_settings = ['webhooks' => [] ];
                $project->settings = array_merge($project->settings, $new_settings);
            }
            /* 10. 
                Добавить в вебхуки битрикса поле params*/
            foreach($project->settings['webhooks'] as $webhook){
                if($webhook['type'] === Project::WEBHOOK_BITRIX24){
                    print('ОК!\n');
                    $new_settings = $project->settings;
                    $new_settings['webhooks'][$webhook['name']]['params'] = [
                        'TITLE' => null,
                        'STATUS_ID' => null,
                        'SOURCE_ID' => null,
                        'SOURCE_DESCRIPTION' => null,
                        'OPENED' => 'Y',
                    ];

                    $project->settings = array_merge($project->settings, $new_settings);
                }
            }
            
            /* 11. 
                Добавить в настройки проекта поля "Включено" и "Описание" */
            if(!array_key_exists('enabled', $project->settings)){
                $new_settings = $project->settings;
                $new_settings['enabled'] = true;
                $project->settings = array_merge($project->settings, $new_settings);
            }
            if(!array_key_exists('description', $project->settings)){
                $new_settings = $project->settings;
                $new_settings['description'] = null;
                $project->settings = array_merge($project->settings, $new_settings);
            }
            
            /* 12. 
                Добавить в настройки email поле "Шаблон" */
            if(!array_key_exists('template', $project->settings['email'])){
                $new_settings = $project->settings;
                $new_settings['email']['template'] = Project::TEMPLATE_VIEW;
                $project->settings = array_merge($project->settings, $new_settings);
            }

            /* 13.
                Добавить цвет по умолчанию*/
            if(!array_key_exists('color', $project->settings)){
                    $new_settings = $project->settings;
                    $new_settings['color'] = dechex(rand(0, 16777215));
                    $project->settings = array_merge($project->settings, $new_settings);
            }
            else{
                if($project->settings['color'] === Project::DEFAULT_COLOR){
                    $new_settings = $project->settings;
                    $new_settings['color'] = dechex(rand(0, 16777215));
                    $project->settings = array_merge($project->settings, $new_settings);
                }
            }


            /* 14.
                Установить владельца лидов*/
            foreach($project->leads as $lead){
                if(isset($lead->owner) && is_null($lead->owner))
                    $lead->owner = 'API';
            }

            /* 15.
                Добавить в настройки проекта опцию "Срок годности лида" */
            if(!array_key_exists('leadValidDays', $project->settings)){
                $new_settings = $project->settings;
                $new_settings['leadValidDays'] = 0;
                $project->settings = array_merge($project->settings, $new_settings);
            }

            /* 16.*
                Добавить в настройки проекта раздел "SMS" */
            if(!array_key_exists('SMS', $project->settings)){
                $new_settings = $project->settings;
                $new_settings['SMS']['enabled'] = false;
                $new_settings['SMS']['text'] = '';

                $project->settings = array_merge($project->settings, $new_settings);
            }

            $project->save();
        }
    }
}
