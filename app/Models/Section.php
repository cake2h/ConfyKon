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
    ];

    public function konf()
    {
        return $this->belongsTo(Konf::class, 'konf_id', 'id');
    }
}
