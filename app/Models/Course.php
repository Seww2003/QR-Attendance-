<?php
// app/Models/Course.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_code',
        'course_name',
        'description',
        'lecturer_id',
    ];

    // Relationships
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class)
            ->withPivot('enrolled_date', 'status')
            ->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(ClassSession::class);
    }

    // ADD THIS NEW RELATIONSHIP
  public function attendances()
    {
        return $this->hasManyThrough(
            Attendance::class,
            ClassSession::class,
            'course_id',      // Foreign key on ClassSession table
            'session_id',     // Foreign key on Attendance table (THIS WAS THE ISSUE!)
            'id',             // Local key on Course table
            'id'              // Local key on ClassSession table
        );
    }
}