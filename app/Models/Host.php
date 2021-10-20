<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
    use HasFactory;

    //Свойства
    protected $fillable = ['host', 'project_id'];


    //Методы
    public function project() //Возвращает проект, к которому относится хост
    {
        return $this->belongsTo(Project::class);
    }

    public function isInProject(string $project): bool //Проверяет, относится ли хост к проекту, по имени проекта
    {
        return (strcmp($this->project->name, $project) == 0) ? true : false;
    }
}
