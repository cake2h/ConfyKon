<?php

namespace App\Models;

use App\Notifications\PasswordReset;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'birthday',
        'city',
        'phone_number',
        'edu_id',
        'study_place',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function education_level()
    {
        return $this->belongsTo(EducationLevel::class, 'edu_id', 'id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isModerator()
    {
        return $this->role === 'moderator';
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'user_id');

    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
