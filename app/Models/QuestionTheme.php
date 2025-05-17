<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionTheme extends Model
{
    protected $fillable = ['name'];

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }
} 