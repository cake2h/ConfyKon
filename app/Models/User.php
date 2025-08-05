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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Conference;
use App\Models\Section;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'email',
        'password',
        'birthday',
        'city_id',
        'phone_number',
        'education_level_id',
        'study_place_id',
        'role',
        'consent_to_mailing',
        'balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function education_level(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function study_place(): BelongsTo
    {
        return $this->belongsTo(StudyPlace::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    public function isAdmin()
    {
        return Conference::where('user_id', $this->id)->exists();
    }

    public function isModerator()
    {
        return Section::where('user_id', $this->id)->exists();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'user_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'user_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function hasConferences()
    {
        return Conference::where('user_id', $this->id)->exists();
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
        'consent_to_mailing' => 'boolean',
        'birthday' => 'date',
        'balance' => 'decimal:2',
    ];
}
