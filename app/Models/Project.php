<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'api_token'
    ];

    public function isOwner(): bool
    {
        return Project::findOrFail($this->id)->user_id === Auth::id();
    }

    public function leads()
    {
        return $this->hasMany(Leads::class, 'project_id');
    }

    public function leadsToday()
    {
        return $this->hasMany(Leads::class, 'project_id')->whereDate('created_at', Carbon::today());
    }

    /**
     * Return the user project
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
