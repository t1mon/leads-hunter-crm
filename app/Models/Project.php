<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'host',
        'user_id',
        'settings',
        'api_token'
    ];

    //Настройки по умолчанию
    protected $attributes = [
        'settings' =>
        '{
            "email":
            {
                "enabled": true,
                "fields": ["name", "phone"]
            }
        }',
    ];

    protected $casts = ['settings' => 'array'];

    public function isOwner(): bool
    {
        return Project::findOrFail($this->id)->user_id === Auth::id();
    }

    public function hosts()
    {
        return $this->hasMany(Host::class);
    }

    public function user_permissions()
    {
        return $this->hasMany(Project\UserPermissions::class);
    }

    public function emails()
    {
        return $this->hasMany(Email::class);
    }

    public function leads()
    {
        return $this->hasMany(Leads::class, 'project_id');
    }

    public function leadsToday()
    {
        return $this->hasMany(Leads::class, 'project_id')->whereDate('created_at', Carbon::today());
    }

    /**
     * Return the user project
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isAdmin() //Проверяет, является ли текущий пользователь администратором проекта
    {
        $user = Project\UserPermissions::where(['project_id' => $this->id, 'user_id' => Auth::user()->id])->first();

        //Проверка, добавлен ли пользователь в проект
        if($user->exists()){
            //Проверка, имеет ли пользователь роль админа
            return
                $user->role_id == Role::ROLE_ADMIN_ID ? true : false;
        }
        else
            return false;
    }
}