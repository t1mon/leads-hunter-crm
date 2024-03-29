<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

use App\Models\Project\Project;
use App\Models\Project\UserPermissions;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'provider', 'provider_id', 'registered_at', 'api_token'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'registered_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    const USER_NOT_FOUND = 'user_not_found';

    /**
     * Get the user's fullname titleized.
     */
    public function getFullnameAttribute(): string
    {
        return Str::title($this->name);
    }

    /**
     * Scope a query to only include users registered last week.
     */
    public function scopeLastWeek(Builder $query): Builder
    {
        return $query->whereBetween('registered_at', [carbon('1 week ago'), now()])
                     ->latest();
    }

    /**
     * Scope a query to order users by latest registered.
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('registered_at', 'desc');
    }

    /**
     * Scope a query to filter available author users.
     */
    public function scopeAuthors(Builder $query): Builder
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('roles.name', Role::ROLE_MANAGER)
                  ->orWhere('roles.name', Role::ROLE_WATCHER);
        });
    }

    /**
     * Проверяет, добавлен ли пользователь в проект
     */
    public function isInProject(Project $project): bool
    {
        return $this->getPermissionsForProject($project)->exists();
    } //isInProject

    public function hasRole(string $role): bool
    {
        return $this->roles->where('name', $role)->isNotEmpty();
    }

    public function isAdmin(): bool
    {
        //TODO Переделать авторизацию, чтобы больше не держать этот метод
        return $this->hasRole(Role::ROLE_ADMIN);
    }

    //Проверяет, является ли пользователь менеджером определённого проекта
    public function isManagerFor(Project|int $project): bool
    {
        $permissions = $this->getPermissionsForProject($project);
        if( is_null($permissions) )
            return false;
        else
            return $permissions->role === Role::ROLE_MANAGER;
    } //isManagerFor

    //Проверяет, является ли пользователь наблюдателем определённого проекта
    public function isWatcher(Project|int $project): bool
    {
        $permissions = $this->getPermissionsForProject($project);
        if( is_null($permissions) )
            return false;
        else
            return $permissions->role === Role::ROLE_WATCHER;
    } //isWatcher

    /**
     * Return the user's posts
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    /**
     * Return the user's comments
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'author_id');
    }

    /**
     * Return the user's likes
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'author_id');
    }

    /**
     * Return the user's roles
     */
    public function roles(): belongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * Return the user's projects
     */
    public function projects(): HasMany //Возвращает проекты, создателями которых является пользователь
    {
        return $this->hasMany(Project::class, 'user_id');
    }

    public function getAllProjects() //Возвращает ВСЕ проекты, на которые назначен (или является создателем) пользователь
    {
        $ids = UserPermissions::where(['user_id' => $this->id])->pluck('project_id');
        return Project::findMany($ids);
    } //getAllProjects

    public function permissions() //Получить ВСЕ разрешения пользователя по ВСЕМ проектам
    {
        return $this->hasMany(UserPermissions::class);
    } //permissions

    public function getPermissionsForProject(Project|int $project) //Получить разрешения пользователя по конкретному проекту
    {
        return UserPermissions::where([
            'project_id' => $project instanceof Project ? $project->id : $project,
            'user_id' => $this->id
        ])->first();
    } //getPermissionsForProject

    
}
