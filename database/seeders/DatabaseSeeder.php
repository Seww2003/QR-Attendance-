<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Lecturer;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@qr.edu',
            'password' => Hash::make('123456789V'),
            'role' => 'admin',
            'nic' => '123456789V',
            'phone' => '0771234567',
            'is_active' => true,
        ]);

        // Create Lecturer
        $lecturerUser = User::create([
            'name' => 'Dr. John Doe',
            'email' => 'lecturer@qr.edu',
            'password' => Hash::make('987654321V'),
            'role' => 'lecturer',
            'nic' => '987654321V',
            'phone' => '0777654321',
            'is_active' => true,
        ]);

        $lecturer = Lecturer::create([
            'user_id' => $lecturerUser->id,
            'employee_id' => 'EMP001',
            'department' => 'Computer Science',
        ]);

        // Create Student
        $studentUser = User::create([
            'name' => 'Student One',
            'email' => 'student@qr.edu',
            'password' => Hash::make('456789123V'),
            'role' => 'student',
            'nic' => '456789123V',
            'phone' => '0771112233',
            'is_active' => true,
        ]);

        $student = Student::create([
            'user_id' => $studentUser->id,
            'student_id' => 'STU001',
            'batch' => '2023',
            'faculty' => 'Computing',
        ]);
    }
}