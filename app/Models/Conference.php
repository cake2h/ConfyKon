<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Conference extends Model
{
    use HasFactory;

    protected $table = 'conferences';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'date_start',
        'date_end',
        'deadline_applications',
        'deadline_reports',
        'city_id',
        'address',
        'user_id',
        'format_id',
        'min_age',
        'max_age'
    ];

    protected $casts = [
        'date_start' => 'datetime',
        'date_end' => 'datetime',
        'deadline_applications' => 'datetime',
        'deadline_reports' => 'datetime'
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function format()
    {
        return $this->belongsTo(Format::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'conference_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'conference_id');
    }

    public function educationLevels()
    {
        return $this->belongsToMany(EducationLevel::class, 'conference_education_levels', 'conference_id', 'education_level_id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    public function getRegistrationDeadlineAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getDateStartAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getDateEndAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getDeadlineApplicationsAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
} 