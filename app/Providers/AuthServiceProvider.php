<?php

namespace App\Providers;

use App\Models\Leads;
use App\Models\Project\Project;
use App\Models\User;
use App\Models\Project\UserPermissions;
use App\Policies\LeadsPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\UserPermissionsPolicy;
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
