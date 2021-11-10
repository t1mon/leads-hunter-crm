<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const ROLE_WATCHER = 'watcher';
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    
    const ROLE_WATCHER_ID = 1;
    const ROLE_ADMIN_ID = 2;
    const ROLE_MANAGER_ID = 3;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
