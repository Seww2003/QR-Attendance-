<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    protected $fillable = [
        'course_id', 'topic', 'date', 'start_time', 'end_time', 
        'is_active', 'qr_token', 'qr_generated_at'
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
        'qr_generated_at' => 'datetime'
    ];

    // Relationship with Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relationship with Attendances
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'session_id');
    }
}