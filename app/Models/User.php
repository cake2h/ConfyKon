<?php

namespace App\Models;

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
        'surname',
        'midname',
        'email',
        'password',
        'birthday',
        'city',
        'phone_number',
        'edu_id',
        'study_place',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function education_level()
    {
        return $this->belongsTo(EducationLevel::class, 'edu_id', 'id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function inTeam()
    {
        return $this->role === 'inteam';
    }

    public function own_konf(): HasMany
    {
        return $this->hasMany(Konf::class);

    }
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
