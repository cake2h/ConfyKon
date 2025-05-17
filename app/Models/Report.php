<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_theme',
        'file_path',
        'report_status_id'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function reportStatus()
    {
        return $this->belongsTo(ReportStatus::class, 'report_status_id');
    }

    public function reportComments()
    {
        return $this->hasMany(ReportComment::class);
    }
} 