<?php

namespace App\Models\Project\Lead;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Leads;
use App\Models\Project\Project;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'leads_comments';
    protected $fillable = ['user_id', 'lead_id', 'project_id', 'comment_body'];

    public function user(){ //Получить пользователя, который написал комментарий
        return $this->belongsTo(User::class);
    } //user
    
    public function lead(){ //Получить лид, к которому относится комментарий
        return $this->belongsTo(Leads::class, 'leads_id');
    } //lead

    public function project(){ //Получить проект, к которому относится комментарий
        return $this->belongsTo(Project::class);
    } //project
}
