<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramID extends Model
{
    use HasFactory;

    //Типы контакта
    const TYPE_GROUP = 'group'; //Группа
    const TYPE_PRIVATE = 'private'; //Линое сообщение

    protected $table = 'telegram_ids';
    protected $fillable = ['project_id', 'name', 'type'];
    
}
