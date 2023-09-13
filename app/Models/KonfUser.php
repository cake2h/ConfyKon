<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfUser extends Model
{
    use HasFactory;

    protected $table = 'konf_users';

    protected $fillable = [
        'konf_id',
        'user_id',
        'name_project',
    ];
    public static function hasUserSubscribedToConference($konfId, $userId)
    {
        return static::where('konf_id', $konfId)
            ->where('user_id', $userId)
            ->exists();
    }
}
