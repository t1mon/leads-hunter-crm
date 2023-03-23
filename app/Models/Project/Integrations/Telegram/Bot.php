<?php

namespace App\Models\Project\Integrations\Telegram;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bot extends Model
{
    protected $table = 'integrations_tg_bots';

    protected $fillable = [
        'username',
        'api_token',
        'webhook_token', //Токен, по которому будет распознаваться, для какого бота пришёл запрос на вебхук
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     *      Отношения
     */
    public function chats(): HasMany
    {
        return $this->hasMany(related: Chat::class, foreignKey: 'bot_id');
    } //chats

    /**
     *      Фильтры
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    } //scopeEnabled

    public function scopeDisabled($query)
    {
        return $query->where('enabled', false);
    } //scopeDisabled
}
