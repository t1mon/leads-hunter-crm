<?php

namespace App\Models\Project;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
    use HasFactory;

    const HOST_NOT_FOUND = 'host_not_found';

    protected $fillable = ['host', 'project_id', 'user_id'];


    //Фильтры
    public function scopeFrom($query, Project|int $project)
    {
        return $query->where('project_id', $project instanceof Project ? $project->id : $project);
    } //scopeFrom

    //Отношения
    public function project() //Возвращает проект, к которому относится хост
    {
        return $this->belongsTo(Project::class);
    }
}
