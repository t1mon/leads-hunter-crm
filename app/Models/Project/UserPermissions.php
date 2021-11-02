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
        'role_id',
        'manage_users', //Право добавлять и удалять пользователей в проект
        'manage_settings', //Право изменять настройки проекта
        'manage_payments', //Право осуществлять платежи по проекту
        'view_journal', //Право на просмотр журнала
        'view_fields' //Поля журнала, которые пользователь может видеть
    ];

    protected $attributes = [
        'role_id' => Role::ROLE_WATCHER_ID, //Роль "Наблюдатель"
        'manage_users' => false,
        'manage_settings' => false,
        'manage_payments' => false,
        'view_journal' => true,
        'view_fields' => '["email", "city", "host"]',
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

    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class);
    } //role

    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class);
    } //project

    public function resetFieldsToDefault() //Сбросить настройки доступных полей журнала
    {
        $this->view_fields = $this->getOriginal('view_fields');
    } //resetFieldsToDefault

    public function resetAllToDefault() //Сбросить ВСЕ настройки
    {
        $this->fill($this->getOriginal());
    } //resetAllToDefault
}
