<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Konf extends Model
{
    protected $fillable = [
        'name',
        'description',
        'date_start',
        'date_end',
        'registration_deadline',
        'deadline'
    ];

    protected $casts = [
        'date_start' => 'datetime',
        'date_end' => 'datetime',
        'registration_deadline' => 'datetime',
        'deadline' => 'datetime',
    ];

    public function getRegistrationDeadlineAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getDateStartAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getDateEndAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getDeadlineAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
} 