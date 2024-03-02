<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailsForSend extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
    ];
}
