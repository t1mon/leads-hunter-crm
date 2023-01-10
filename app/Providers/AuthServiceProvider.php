<?php

namespace App\Providers;

use App\Models\Leads;
use App\Models\Project\Project;
use App\Models\Project\Lead\Comment;
use App\Models\User;
use App\Models\Project\UserPermissions;
use App\Policies\ProjectPolicy; //Старая версия политики
// use App\Policies\V2\Project\ProjectPolicy; //Новая версия политики
use App\Policies\UserPermissionsPolicy;
use App\Policies\V2\LeadPolicy;
use App\Policies\V2\Project\Lead\CommentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Comment' => 'App\Policies\CommentPolicy',
        'App\Models\Post' => 'App\Policies\PostPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Media' => 'App\Policies\MediaPolicy',
        Project::class => ProjectPolicy::class,
        UserPermissions::class => UserPermissionsPolicy::class,
        Comment::class => CommentPolicy::class,
        Leads::class => LeadPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

//        \Gate::define('test', function (User $user,Project $project) {
//            return $project->isOwner();
//        });
    }
}
