<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date_start',
        'date_end',
        'event_place',
        'conference_id',
        'user_id',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class, 'conference_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function moder()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'section_id', 'id');
    }
}
