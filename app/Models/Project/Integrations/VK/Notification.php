<?php

namespace App\Models\Project\Integrations\VK;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'integrations_vk_notifications';

    protected $fillable = [
        'subscription_id',
        'notification_id',
        'resource_id',
        'resource',
        'callback_url',
        'created',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    //
    //  Связи
    //
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(related: Subscription::class, foreignKey: 'subscription_id');
    } //subscription

    //
    //  Фильтры
    //
    public function scopeSubscription(Builder $query, Subscription|int $subscription): Builder
    {
        return $this->where('subscription_id', $subscription instanceof Subscription ? $subscription->id : $subscription);
    } //scopeSubscription
}
