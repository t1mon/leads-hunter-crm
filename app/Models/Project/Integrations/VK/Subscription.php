<?php

namespace App\Models\Project\Integrations\VK;

use App\Models\Project\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $table = 'integrations_vk_subscriptions';
    
    protected $fillable = [
        'project_id',
        'user_id',
        'name',
        'enabled',
        'resource',
        'subscription_id',
        'api_token',
        'refresh_token',
        'expires_at',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    //
    //  Константы
    //
    const URL_GET_TOKEN = 'https://vk.com/api/v2/oauth2/token.json'; //URL для получения и обновления токена
    const URL_SUBSCRIPTION = 'https://vk.com/api/v3/subscription.json'; //URL для получения и создания подписок

    //
    //  Связи
    //
    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id');
    } //user

    public function project(): BelongsTo
    {
        return $this->belongsTo(related: Project::class, foreignKey: 'project_id');
    } //project

    //
    //  Фильтры
    //
    public function scopeUser(Builder $query, User|int $user): Builder
    {
        return $query->where('user_id', $user instanceof User ? $user->id : $user);
    } //scopeUser

    public function scopeProject(Builder $query, Project|int $project): Builder
    {
        return $query->where('project_id', $project instanceof Project ? $project->id : $project);
    } //scopeProject

    public function scopeEnabled(Builder $query, bool $enabled = true): Builder
    {
        return $query->where('enabled', $enabled);
    } //scopeEnabled

    //
    //  Рабочие методы
    //
    public function isExpired(): bool
    {
        $now = Carbon::now();
        $expires_at = Carbon::parse(time: $this->expires_at, tz: 'UTC');

        return Carbon::now()->greaterThanOrEqualTo($expires_at);
    } //isExpired
}
