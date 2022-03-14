<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Project\Project;

class VKForm extends Model
{
    use HasFactory;

    protected $table = 'vk_forms';
    protected $fillable = ['project_id', 'url', 'confirmation_response', 'group_id', 'host', 'source', 'enabled'];
    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function project(){
        return $this->belongsTo(Project::class);
    } //project
}
