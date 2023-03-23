<?php

namespace App\Models\Project\Integrations\Telegram;

use App\Models\Leads;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Chat extends Model
{
    protected $table = 'integrations_tg_chats';

    protected $fillable = [
        'project_id',
        'username',
        'chat_id',
        'invite',
        'confirmed',
        'bot_id',
        'format',
        'enabled',
    ];

    protected $casts = [
        'confirmed' => 'boolean',
        'enabled' => 'boolean',
    ];

    /**
     *      Отношения
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(related: Project::class, foreignKey: 'project_id');
    } //project

    public function bot(): BelongsTo
    {
        return $this->belongsTo(related: Bot::class, foreignKey: 'bot_id');
    } //bot

    /**
     *      Фильтры
     */
    public function scopeFrom($query, Project|int|array $project)
    {
        if(is_array($project)){
            $projects = array_map(callback: function($item){
                return $item instanceof Project ? $item->id : $item;
            }, array: $project);

            return $query->whereIn('project_id', $projects);
        }
        else
            return $query->where('project_id', $project instanceof Project ? $project->id : $project);
    } //scopeFrom

    public function scopeUsername($query, string|array $username)
    {
        if(is_array($username))
            return $query->whereIn('username', $username);
        else
            return $query->where('username', $username);
    } //scopeUsername

    public function scopeChat($query, string|array $chat_id)
    {
        if(is_array($chat_id))
            return $query->whereIn('chat_id', $chat_id);
        else
            return $query->where('chat_id', $chat_id);
    } //scopeUsername

    public function scopeConfirmed($query)
    {
        return $query->where('confirmed', true);
    } //scopeUsername

    public function scopePending($query) //Поиск неподтверждённых интеграций
    {
        return $query->where('confirmed', false);
    } //scopePending

    public function scopeFor($query, Bot|int|array $bot)
    {
        if(is_array($bot)){
            $bots = array_map(callback: function($item){
                return $item instanceof Bot ? $item->id : $item;
            }, array: $bot);

            return $query->whereIn('project_id', $bots);
        }
        else
            return $query->where('bot_id', $bot instanceof Project ? $bot->id : $bot);
    } //scopeFor

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    } //scopeEnabled

    public function scopeDisabled($query) //Поиск неподтверждённых интеграций
    {
        return $query->where('enabled', false);
    } //scopeDisabled

    /**
     *      Рабочие методы
     */
    public function composeMessage(Leads $lead): string //Подставить значения лида в сообщение
    {
        if(empty($this->format))
            return '';

        $result = $this->format;

        foreach(Leads::getFields() as $field)
            $result = Str::replace(search: '$'.$field, replace: $lead->$field, subject: $result);

        return $result;
    } //composeMessage
}