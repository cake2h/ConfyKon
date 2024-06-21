<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'otherAuthors',
        'file_path',
        'user_id',
        'section_id',
        'type_id'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
