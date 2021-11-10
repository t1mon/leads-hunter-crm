<?php

namespace App\Models\Project;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    //Свойства
    protected $fillable = ['email', 'project_id'];

    //Методы
    public function project() //Возвращает проект, к которому относится адрес почты
    {
        return $this->belongsTo(Project::class);
    }
}
