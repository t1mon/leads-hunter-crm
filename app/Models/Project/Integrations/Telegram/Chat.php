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
        'title',
        'type',
        'chat_id',
        'invite',
        'format',
        'confirmed',
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

    public function scopeTitle($query, string|array $title)
    {
        if(is_array($title))
            return $query->whereIn('title', $title);
        else
            return $query->where('title', $title);
    } //scopeTitle

    public function scopeChat($query, string|array $chat_id)
    {
        if(is_array($chat_id))
            return $query->whereIn('chat_id', $chat_id);
        else
            return $query->where('chat_id', $chat_id);
    } //scopeUsername

    public function scopeInvite($query, string $invite)
    {
        return $query->where('invite', $invite);
    } //scopeInvite

    public function scopeConfirmed($query)
    {
        return $query->where('confirmed', true);
    } //scopeUsername

    public function scopePending($query) //Поиск неподтверждённых интеграций
    {
        return $query->where('confirmed', false);
    } //scopePending

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    } //scopeEnabled

    public function scopeDisabled($query) //Поиск неподтверждённых интеграций
    {
        return $query->where('enabled', false);
    } //scopeDisabled

    /**
     *      Геттеры
     */
    public function isPrivate(): bool
    {
        return $this->type === 'private';
    } //isPrivate

    public function getStartCommandPattern(): string //Составляет шаблон команды для проверки в зависимости от типа чата
    {
        return $this->isPrivate()
            ? '/^\/start$/'
            : '/^\/start@' . env('TELEGRAM_BOT_NAME') . '$/';
    } //getStartCommand

    public function getStopCommandPattern(): string
    {
        return $this->isPrivate()
            ? '/^\/stop$/'
            : '/^\/stop@' . env('TELEGRAM_BOT_NAME') . '$/';
    } //getStopCommandPattern

    public function getConfirmCommandPattern(): string
    {
        return $this->isPrivate()
            ? '/^' . $this->invite . '$/'
            : '/^@' . env('TELEGRAM_BOT_NAME') . ' ' . $this->invite . '$/';
    } //getConfirmCommandPattern

    /**
     *      Рабочие методы
     */
    public static function generateInvite(): string
    {
        return Str::random(6);
    } //generateInvite

    public function composeMessage(Leads $lead): string //Подставить значения лида в сообщение
    {
        if(empty($this->format))
            return '';

        $result = $this->format;

        foreach(Leads::getFields() as $field)
            if(Str::contains(haystack: $result, needles: '$'.$field))
                $result = Str::replace(search: '$'.$field, replace: "{$lead->$field}", subject: $result);

        $result = Str::replace(search: ['\n'], replace: PHP_EOL, subject: $result);

        return $result;
    } //composeMessage

    public function enable(): void
    {
        $this->update(['enabled' => true]);
    } //enable

    public function disable(): void
    {
        $this->update(['enabled' => false]);
    } //enable

    public function toggle(): void
    {
        $this->update(['enabled' => !$this->enabled]);
    } //toggle
}