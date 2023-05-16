<?php

namespace App\Models\Project\Integrations\Calltracking;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    protected $table = 'integrations_calltracking_logs';

    protected $fillable = [
        'project_id',
        'phone_id',
        'log',
    ];

    protected $casts = [
        'log' => 'array',
    ];

    //
    //  Связи
    //
    public function project(): BelongsTo
    {
        return $this->belongsTo(related: Project::class, foreignKey: 'project_id');
    } //project

    public function phone(): BelongsTo
    {
        return $this->belongsTo(related: Phone::class, foreignKey: 'phone_id');
    } //integration

    //
    //  Фильтры
    //
    public function scopeProject(Builder $query, Project|int $project): Builder
    {
        return $query->where('project_id', $project instanceof Project ? $project->id : $project);
    } //scopeProject

    public function scopeIntegration(Builder $query, Phone|int $phone): Builder
    {
        return $query->where('phone_id', $phone instanceof Phone ? $phone->id : $phone);
    } //scopeIntegration
}
