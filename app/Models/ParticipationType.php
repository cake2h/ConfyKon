<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipationType extends Model
{
    use HasFactory;

    protected $table = 'participation_types';

    protected $fillable = [
        'name'
    ];

    public function applications()
    {
        return $this->hasMany(Application::class, 'role_id');
    }
}
