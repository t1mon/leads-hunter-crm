<?php

namespace App\Models\Project\Integrations;

use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailReader extends Model
{
    protected $table = "integrations_email_reader";

    protected $fillable = [
        'user_id',
        'project_id',
        'email',
        'password',
        'host',
        'template',
        'enabled',
        'interval',
        'mails_per_time',
        'mark_as_read',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'interval' => 'integer',
        'mails_per_time' => 'integer',
        'mark_as_read' => 'boolean',
    ];

    //
    //      Отношения
    //
    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id');
    } //user

    public function project(): BelongsTo
    {
        return $this->belongsTo(related: Project::class, foreignKey: 'project_id');
    } //project

    //
    //      Фильтры
    //
    public function scopeAddedBy($query, User|int $user)
    {
        return $query->where('user_id', $user instanceof User ? $user->id : $user);
    } //scopeAddedBy

    public function scopeFrom($query, Project|int $project)
    {
        return $query->where('project_id', $project instanceof Project ? $project->id : $project);
    } //scopeFrom

    //
    //      Рабочие методы
    //
    public function getMail() //Проверить почтовый ящик и получить письма
    {
        try{
            $inbox = imap_open(mailbox: $this->host . 'INBOX', user: $this->email, password: $this->password);
            if(!$inbox)
                throw new \Exception(message: 'Ошибка открытия почтового ящика:' . imap_last_error());
        }
        catch(\Exception $e){

        }
    } //getMail
}
