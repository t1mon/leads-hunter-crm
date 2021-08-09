<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'email_notifications', 'telegram_notifications'
    ];

    protected $casts = [
        'email_notifications' => 'array',
        'telegram_notifications' => 'array'
    ];
}
