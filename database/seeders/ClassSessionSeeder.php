<?php
// database/seeders/ClassSessionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassSession;
use App\Models\Course;
use Carbon\Carbon;

class ClassSessionSeeder extends Seeder
{
    public function run()
    {
        // Get first course
        $course = Course::first();
        
        if ($course) {
            ClassSession::create([
                'course_id' => $course->id,
                'topic' => 'Introduction to Programming',
                'date' => Carbon::today(),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'is_active' => true,
            ]);
            
            ClassSession::create([
                'course_id' => $course->id,
                'topic' => 'Database Management',
                'date' => Carbon::tomorrow(),
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'is_active' => true,
            ]);
            
            $this->command->info('Test sessions created!');
        } else {
            $this->command->error('No courses found. Please create a course first.');
        }
    }
}