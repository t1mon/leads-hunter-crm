<?php

namespace App\Models\Project\Integrations\Calltracking;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Phone extends Model
{
    protected $table = 'integrations_calltracking_phones';

    protected $fillable = [
        'project_id',
        'phone',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    //
    //  Связи
    //
    public function project(): BelongsTo
    {
        return $this->belongsTo(related: Projectt::class, foreignKey: 'project_id');
    } //project

    //
    //  Фильтры
    //
    public function scopeProject(Builder $query, Project|int $project): Builder
    {
        return $query->where('project_id', $project instanceof Project ? $project->id : $project);
    } //scopeProject

    public function scopePhone(Builder $query, string|int $phone): Builder
    {
        return $query->where('phone', $phone);
    } //scopePhone

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('enabled', true);
    } //scopeActive
}
