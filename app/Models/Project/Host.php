<?php

namespace App\Models\Project;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
    use HasFactory;

    const HOST_NOT_FOUND = 'host_not_found';

    //Свойства
    protected $fillable = ['host', 'project_id'];


    //Методы
    public function project() //Возвращает проект, к которому относится хост
    {
        return $this->belongsTo(Project::class);
    }
}
