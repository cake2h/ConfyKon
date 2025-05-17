<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faq';
    
    protected $fillable = [
        'name',
        'answer',
        'question_theme_id',
        'conference_id'
    ];

    public function theme()
    {
        return $this->belongsTo(QuestionTheme::class, 'question_theme_id');
    }

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
} 