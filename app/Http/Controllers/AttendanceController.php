<?php
// app/Http/Controllers/AttendanceController.php

// app/Http/Controllers/AttendanceController.php

namespace App\Http\Controllers;

use App\Models\ClassSession;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // Mark Attendance
    public function markAttendance(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:class_sessions,id',
        ]);

        $student = Auth::user()->student;
        
        // Check if already marked
        $existing = Attendance::where('session_id', $request->session_id)
            ->where('student_id', $student->id)
            ->exists();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You have already marked attendance for this session'
            ]);
        }

        // Mark attendance
        Attendance::create([
            'session_id' => $request->session_id,
            'student_id' => $student->id,
            'attended_at' => now(),
            'status' => 'present',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully!'
        ]);
    }

    // View Student's Attendance History
    public function myAttendance()
    {
        $student = Auth::user()->student;
        $attendances = Attendance::with(['session.course'])
            ->where('student_id', $student->id)
            ->latest()
            ->paginate(10);

        return view('student.attendance', compact('attendances'));
    }
}