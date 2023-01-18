<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Role;
use App\Models\User;

class UserPermissions extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'project_id',
        'role',
        'view_fields' //Поля журнала, которые пользователь может видеть
    ];

    protected $casts = [
        'view_fields' => 'array'
    ];

    public const ALLOWED_BASIC_FIELDS = [ //Базовые поля лида, которые доступны любому пользователю
        'id',
        'name',
        'patronymic',
        'surname',
        'phone',
        'created_at',
    ];

    /**
     *      Фильтры
     */
    public function scopeFrom($query, Project|int $project) //Поиск по проекту
    {
        return $query->where('project_id', $project instanceof Project ? $project->id : $project);
    } //scopeFrom

    public function scopeFor($query, User|int $user) //Поиск по пользователю
    {
        return $query->where('user_id', $user instanceof User ? $user->id : $user);
    } //scopeFor

    /**
     *      Отношения
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    } //user

    // public function role()
    // {
    //     return $this->belongsTo(\App\Models\Role::class);
    // } //role

    public function project()
    {
        return $this->belongsTo(related: \App\Models\Project\Project::class, foreignKey: 'project_id');
    } //project

    /**
     *      Служебные методы
     */
    public function isOwner(): bool
    {
        return $this->project->user_id === $this->user_id;
    } //isOwner

    public function isManager(): bool
    {
        return $this->role === Role::ROLE_MANAGER;
    } //isManager

    public function isJuniorManager(): bool
    {
        return $this->role === Role::ROLE_JUNIOR_MANAGER;
    }
    
    public function isWatcher(): bool
    {
        return $this->role === Role::ROLE_WATCHER;
    } //isWatcher

    public function fieldAllowed(string $field): bool //Проверяет, доступно ли поле пользователю
    {
        if(in_array(needle: $field, haystack: self::ALLOWED_BASIC_FIELDS) )
            return true;
        
        return in_array(needle: $field, haystack: $this->view_fields);
    }
}
