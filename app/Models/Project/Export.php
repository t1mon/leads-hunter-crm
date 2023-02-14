<?php

namespace App\Models\Project;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Export extends Model
{
    protected $table = "project_exports";

    protected $fillable = [
        'project_id',
        'user_id',
        'name',
        'expires_at',
        'finished',
        'download_url',
    ];

    /**
     *      Константы
     */
    public const DEFAULT_VALID_FOR = 2; //Количество дней, на протяжении которых можно скачать файл (значение по умолчанию)
    public const LIMIT_PER_USER = 3; //Ограничение по количеству экспортов на пользователя в день (по умолчанию)

    /**
     *      Отношения
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(related: Project::class, foreignKey: 'project_id');
    } //project

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id');
    } //user

    /**
     *      Фильтры
     */
    public function scopeUser($query, User|int $user)
    {
        return $query->where('user_id', $user instanceof User ? $user->id : $user);
    } //scopeUser

    public function scopeProject($query, Project|int $project)
    {
        return $query->where('project_id', $project instanceof Project ? $project->id : $project);
    } //scopeProject

    public function scopeFinished($query)
    {
        return $query->where('finished', true);
    } //scopeFinished

    public function scopeExpired($query)
    {
        return $query->finished()->whereDate('expires_at', '>=', Carbon::now(config('app.config')));
    } //scopeExpired

    public function scopeValid($query)
    {
        return $query->finished()->whereDate('expires_at', '<', Carbon::now(config('app.config')));
    } //scopeValid

    /**
     *      Служебные методы
     */
    public function isValid(): bool
    {
        if(is_null($this->expires_at))
            return false;

        $expirationDate = Carbon::parse(time: $this->expires_at, tz: config('app.timezone'));
        $difference = Carbon::now(config('app.timezone'))->diffInDays(date: $expirationDate);

        return $difference < env(key: 'EXPORT_FILE_IS_VALID_FOR', default: self::DEFAULT_VALID_FOR);
    } //isValid
}
