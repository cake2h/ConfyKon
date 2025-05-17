<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'user_id',
        'presentation_type_id',
        'report_id',
        'participation_type_id',
        'application_status_id',
        'contributors',
        'title'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function presentationType()
    {
        return $this->belongsTo(PresentationType::class);
    }

    public function participationType()
    {
        return $this->belongsTo(ParticipationType::class);
    }

    public function status()
    {
        return $this->belongsTo(ApplicationStatus::class, 'application_status_id');
    }

    public function role()
    {
        return $this->belongsTo(ParticipationType::class, 'participation_type_id');
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
