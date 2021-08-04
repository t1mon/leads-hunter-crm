<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'name', 'phone', 'email', 'cost', 'comment', 'city', 'ip', 'referrer', 'utm'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
