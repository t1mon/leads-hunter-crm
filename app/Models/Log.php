<?php

namespace App\Models;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    use HasFactory;

    protected $table = 'journal';

    protected $fillable = [
        'type',
        'project_id',
        'text',
    ];

    const UPDATED_AT = null;

    public const TYPE_INFO = 1;
    public const TYPE_WARNING = 2;
    public const TYPE_ERROR = 3;

    public const TYPES = [
        self::TYPE_INFO,
        self::TYPE_WARNING,
        self::TYPE_ERROR,
    ];

    //
    //  Связи
    //
    public function project(): BelongsTo
    {
        return $this->belongsTo(related: Project::class);
    } // project

    //
    //  Фильтры
    //
    public function scopeProject(Builder $query, Project|int $project, ?int $type = null): void
    {
        if(is_null($type))
            $query->where('project_id', $project instanceof Project ? $project->id : $project);
        else
            $query->where(['project_id' => $project instanceof Project ? $project->id : $project, 'type' => $type]);
    } // scopeProject
}
