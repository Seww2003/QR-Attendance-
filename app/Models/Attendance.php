<?php
// app/Models/Attendance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'student_id',
        'attended_at',
        'status',
        'remarks',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
    ];

    // Relationships
    public function session()
    {
        return $this->belongsTo(ClassSession::class, 'session_id'); // CHANGED
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}