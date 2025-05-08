<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantRole extends Model
{
    use HasFactory;

    protected $table = 'participant_roles';

    protected $fillable = [
        'name'
    ];

    public function applications()
    {
        return $this->hasMany(Application::class, 'role_id');
    }
} 