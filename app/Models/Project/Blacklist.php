<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blacklist extends Model
{
    protected $fillable = [
        'project_id',
        'phone',
        'name',
        'comment',
    ];


    protected $casts = [
        'phone' => 'string',
    ];

    //
    //  Фильтры
    //
    public function scopeFrom($query, Project|int $project)
    {
        return $query->where('project_id', $project instanceof Project ? $project->id : $project);
    } //scopeFrom

    public function scopePhone($query, string|int $phone)
    {
        return $query->where('phone', $phone);
    } //scopePhone

    //
    //  Отношения
    //
    public function project(): BelongsTo
    {
        return $this->belongsTo(related: Project::class, foreignKey: 'project_id');
    } //project
}
