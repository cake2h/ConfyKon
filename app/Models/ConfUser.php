<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfUser extends Model
{
    use HasFactory;

    protected $table = 'konf_users';

    protected $fillable = [
        'konf_id',
        'user_id',
        'name_project',
        'created_at',
        'updated_at'
    ];

    
}
