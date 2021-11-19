<?php

namespace App\Models\Project;
use App\Models\Leads;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadClass extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'leads_classes';
    protected $fillable = ['name', 'type', 'color', 'project_id'];

    const TYPE_COMMON = 'common'; //Класс из общего набора (доступен для всех проектов)
    const TYPE_LOCAL = 'local'; //Локальный класс (в пределах конкретного проекта)

    public function project()
    {
        //Если класс является общим, вернуть null (он используется во всех проектах и не сможет вернуть ничего конкретного)
        return $this->belongsTo(Project::class);
    }

    public function getLeadsAttribute()
    {
        return Leads::where(['project_id' => $this->project_id, 'class' => $this->name])->get();
    }
}
