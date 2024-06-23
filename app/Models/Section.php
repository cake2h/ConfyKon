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
        'konf_id',
        'moder_id',
        'event_date',
        'event_time',
        'event_place'
    ];

    public function konf()
    {
        return $this->belongsTo(Conf::class, 'konf_id', 'id');
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Application::class, 'section_id', 'id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function moder()
    {
        return $this->belongsTo(User::class, 'moder_id', 'id');
    }
}
