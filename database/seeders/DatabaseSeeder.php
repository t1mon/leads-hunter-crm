<?php

use App\Models\Comment;
use App\Models\MediaLibrary;
use App\Models\Post;
use App\Models\Role;
use App\Models\Token;
use App\Models\User;
use App\Models\Project\Project;
use App\Models\Project\Host;
use App\Models\Project\Email;
use App\Models\Project\UserPermissions;
use App\Models\Leads;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles
        Role::firstOrCreate(['name' => Role::ROLE_WATCHER]);
        $role_admin = Role::firstOrCreate(['name' => Role::ROLE_ADMIN]);
        $role_manager = Role::firstOrCreate(['name' => Role::ROLE_MANAGER]);


        // MediaLibrary
        MediaLibrary::firstOrCreate([]);

        // Users
        $user = User::firstOrCreate(
            ['email' => '1@1.ru'],
            [
                'name' => 'anakin',
                'password' => Hash::make('123456'),
                'email_verified_at' => now()
            ]
        );

        User::firstOrCreate(
            ['email' => '2@2.ru'],
            [
                'name' => 'user_2',
                'password' => Hash::make('1'),
                'email_verified_at' => now()
            ]
        );

        $user->roles()->sync([$role_admin->id]);

        //Projects
        $project = Project::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => 'Test Project',
                'settings' => [
                    'email' => [
                        "enabled" => true,
                        "send_all" => true,
                        "subject" => $project->name,
                        "fields" => []
                    ],
                    
                    'telegram' => [
                        'enabled' => true,
                        'fields' => [],
                    ],
    
                    'timezone' => 'Europe/Samara',
                ],
            ]
        );

        //Hosts
        $host_1 = Host::firstOrCreate(
            [
                'project_id' => $project->id,
                'host' => 'https://host-1.com'
            ],
            [
                'host' => 'https://host-1.com'
            ]
        );

        $host_2 = Host::firstOrCreate(
            [
                'project_id' => $project->id,
                'host' => 'https://host-2.com'
            ],
            [
                'host' => 'https://host-2.com'
            ]
        );

        $host_3 = Host::firstOrCreate(
            [
                'project_id' => $project->id,
                'host' => 'https://host-3.com'
            ],
            [
                'host' => 'https://host-3.com'
            ]
        );

        //Leads
        Leads::firstOrCreate(
            [
                'phone' => 71112223344,
                'project_id' => $project->id,
            ],
            [
                'status' => 'pending',
                'name' => 'Алексей',
                'host' => $host_1->host,
            ]
        );

        Leads::firstOrCreate(
            [
                'phone' => 72223334455,
                'project_id' => $project->id,
            ],
            [
                'status' => 'pending',
                'name' => 'Светлана',
                'host' => $host_2->host,
            ]
        );

        Leads::firstOrCreate(
            [
                'project_id' => $project->id,
                'phone' => 73334445566,
            ],
            [
                'status' => 'pending',
                'name' => 'Евгений',
                'host' => $host_3->host,
            ]
        );

        // Emails
        Email::firstOrCreate(
            [
                'email' => 'dummymail@example.ru',
                'project_id' => $project->id,
            ],
            [
                'email' => 'dummymail@example.ru',
            ]
        );

        Email::firstOrCreate(
            [
                'email' => 'example@mail.ru',
                'project_id' => $project->id,
            ],
            [
                'email' => 'example@mail.ru',
            ]
        );

        Email::firstOrCreate(
            [
                'email' => 'emptybox@box.com',
                'project_id' => $project->id,
            ],
            [
                'email' => 'emptybox@box.com',
            ]
        );

        //User Permissions
        UserPermissions::firstOrCreate(
            [
                'user_id' => $user->id,
                'project_id' => $project->id
            ],
            [
                'role' => $role_admin->name,
                'view_fields' => ['email', 'city', 'host'],
            ]
        );

        // Posts
        $post = Post::firstOrCreate(
            [
                'title' => 'Hello World',
                'author_id' => $user->id
            ],
            [
                'posted_at' => now(),
                'content' => "
                    Welcome to Laravel-blog !<br><br>
                    Don't forget to read the README before starting.<br><br>
                    Feel free to add a star on Laravel-blog on Github !<br><br>
                    You can open an issue or (better) a PR if something went wrong."
            ]
        );

        // Comments
        Comment::firstOrCreate(
            [
                'author_id' => $user->id,
                'post_id' => $post->id
            ],
            [
                'posted_at' => now(),
                'content' => "Hey ! I'm a comment as example."
            ]
        );

        // API tokens
        User::where('api_token', null)->get()->each->update([
            'api_token' => Token::generate()
        ]);
    }
}
