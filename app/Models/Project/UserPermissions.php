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
        return $this->belongsTo(\App\Models\Project::class);
    } //project

    /**
     *      Служебные методы
     */    
    public function isManager(): bool
    {
        return $this->role === Role::ROLE_MANAGER;
    } //isManager
    
    public function isWatcher(): bool
    {
        return $this->role === Role::ROLE_WATCHER;
    } //isWatcher
}
