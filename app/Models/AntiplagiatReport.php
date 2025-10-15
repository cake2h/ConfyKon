<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AntiplagiatReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'doc_id',
        'user_id',
        'title',
    ];

    /**
     * Связь с пользователем
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}