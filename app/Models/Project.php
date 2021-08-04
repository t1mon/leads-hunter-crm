<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'api_token'
    ];

    public function leads()
    {
        return $this->hasMany(Leads::class);
    }

    public function leadsToday()
    {
        return $this->hasMany(Leads::class)->whereDate('created_at', Carbon::today());
    }
}
