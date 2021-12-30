<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Role;

class UserPermissions extends Model
{
    use HasFactory;

    //#############
    //Свойства
    //#############
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

    //#############
    //Методы
    //#############
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
}
