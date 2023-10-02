<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'konf_id',
        'name',
        'moder_id',
    ];

    public function konf()
    {
        return $this->belongsTo(Konf::class, 'konf_id', 'id');
    }
}
