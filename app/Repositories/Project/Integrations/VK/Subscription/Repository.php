<?php

namespace App\Repositories\Project\Integrations\VK\Subscription;

use App\Models\Project\Integrations\VK\Subscription;
use App\Models\Project\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Repository{
    public function query(): Builder
    {
        return Subscription::query();
    }

    public function create(
        Project|int $project,
        User|int $user,
        string $name,
        bool $enabled,
        string $resource,
        string|int $subscription_id,
        string $api_token,
        string $refresh_token,
        int|string $expires_in,
    ): Subscription
    {
        return $this->query()->create([
            'project_id' => $project instanceof Project ? $project->id : $project,
            'user_id' => $user instanceof User ? $user->id : $user,
            'name' => $name,
            'enabled' => $enabled,
            'resource' => $resource,
            'subscription_id' => $subscription_id,
            'api_token' => $api_token,
            'refresh_token' => $refresh_token,
            'refresh_token' => $refresh_token,
            'expires_at' => Carbon::now()->addSeconds($expires_in),
        ]);
    } //create

    public function update(
        Subscription|int $subscription,
        Project|int $project,
        User|int $user,
        string $name,
        bool $enabled,
        string $resource,
        string|int $subscription_id,
        string $api_token,
        string $refresh_token,
        int|string $expires_in,
    ): Subscription
    {
        if(is_int($subscription))
            $subscription = $this->query()->findOrFail($subscription);

        $subscription->update([
            'project_id' => $project instanceof Project ? $project->id : $project,
            'user_id' => $user instanceof User ? $user->id : $user,
            'name' => $name,
            'enabled' => $enabled,
            'resource' => $resource,
            'subscription_id' => $subscription_id,
            'api_token' => $api_token,
            'refresh_token' => $refresh_token,
            'refresh_token' => $refresh_token,
            'expires_at' => Carbon::now()->addSeconds($expires_in),
        ]);

        return $subscription;
    } //update

    public function remove(Subscription|int $subscription): void
    {
        if(is_int($subscription))
            $subscription = $this->query()->findOrFail($subscription);

        $subscription->delete();
    } //remove

    public function activate(Subscription|int $subscription, bool $enabled): Subscription
    {
        if(is_int($subscription))
            $subscription = $this->query()->findOrFail($subscription);

        $subscription->update(['enabled' => $enabled]);

        return $subscription;
    } //activate

    public function updateToken(
        Subscription $subscription,
        string $api_token,
        string $refresh_token,
        string|int $expires_in,
    ): Subscription
    {

        $subscription->update([
            'refresh_token' => $refresh_token,
            'refresh_token' => $refresh_token,
            'expires_at' => Carbon::now()->addSeconds($expires_in),
        ]);

        return $subscription;
    }   
};

?>