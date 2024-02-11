<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    use HasFactory;

    protected $table = 'education_levels';

    public function getTitleAttribute()
    {
        return $this->attributes['title'];
    }
}
