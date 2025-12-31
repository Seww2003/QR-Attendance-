<?php
// app/Http/Controllers/StudentController.php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassSession;
use App\Models\Attendance;



class StudentController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $student = Auth::user()->student;
        $courses = $student->courses()->count();
        $attendance = $student->attendances()->count();

        return view('student.dashboard', compact('courses', 'attendance'));
    }

    // My Courses
    public function myCourses()
    {
        $student = Auth::user()->student;
        $courses = $student->courses()->paginate(10);

        return view('student.courses', compact('courses'));
    }

    // ADD THIS METHOD:
   // app/Http/Controllers/StudentController.php - showCourse method

public function showCourse($course_id)
{
    $student = Auth::user()->student;
    $course = Course::findOrFail($course_id);
    
    // Check if student is enrolled
    if (!$student->courses->contains($course->id)) {
        return redirect()->route('student.courses')->with('error', 'You are not enrolled in this course');
    }

    // Get sessions with attendance status
    $sessions = \DB::table('class_sessions')
        ->leftJoin('attendances', function($join) use ($student) {
            $join->on('class_sessions.id', '=', 'attendances.session_id')
                 ->where('attendances.student_id', '=', $student->id);
        })
        ->where('class_sessions.course_id', $course_id)
        ->select(
            'class_sessions.*', 
            'attendances.attended_at', 
            'attendances.status as attendance_status'
        )
        ->orderBy('class_sessions.date', 'desc')
        ->orderBy('class_sessions.start_time', 'desc')
        ->paginate(10);

    return view('student.course-show', compact('course', 'sessions'));
}

// ================= PROCESS QR =================
     public function processQR(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        $qr_data = $request->qr_data;

        // Format: ATTENDANCE:session_id:qr_token
        $parts = explode(":", $qr_data);
        if(count($parts) !== 3 || $parts[0] !== 'ATTENDANCE') {
            return response()->json(['success' => false, 'message' => 'Invalid QR code format']);
        }

        $session_id = $parts[1];
        $qr_token = $parts[2];

        $session = ClassSession::with('course.lecturer.user')->find($session_id);
        if(!$session) {
            return response()->json(['success' => false, 'message' => 'Session not found']);
        }

        if($session->qr_token !== $qr_token) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired QR code']);
        }

        // Return session details if valid
        return response()->json([
            'success' => true,
            'session_id' => $session->id,
            'course_name' => $session->course->course_name,
            'topic' => $session->topic,
            'date' => $session->date,
            'start_time' => $session->start_time,
            'end_time' => $session->end_time,
            'lecturer_name' => $session->course->lecturer->user->name,
        ]);
    }

    // Mark attendance
    public function markAttendance(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:class_sessions,id'
        ]);

        $student = Auth::user()->student;
        $session = ClassSession::find($request->session_id);

        // Prevent double marking
        $existing = Attendance::where('session_id', $session->id)
                              ->where('student_id', $student->id)
                              ->first();
        if($existing) {
            return response()->json(['success' => false, 'message' => 'Attendance already marked']);
        }

        Attendance::create([
            'session_id' => $session->id,
            'student_id' => $student->id,
            'status' => 'present',
            'marked_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Attendance marked successfully']);
    }
}


// public function markAttendance(Request $request)
// {
//     $request->validate([
//         'session_id' => 'required|integer|exists:class_sessions,id'
//     ]);
    
//     try {
//         $student = Auth::user()->student;
        
//         // Check if already marked
//         $existingAttendance = Attendance::where([
//             ['student_id', $student->id],
//             ['session_id', $request->session_id]
//         ])->first();
        
//         if ($existingAttendance) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Attendance already marked'
//             ], 400);
//         }
        
//         // Create attendance record
//         $attendance = Attendance::create([
//             'session_id' => $request->session_id,
//             'student_id' => $student->id,
//             'attended_at' => now(),
//             'status' => 'present',
//             'remarks' => 'Marked via QR code scanner'
//         ]);
        
//         return response()->json([
//             'success' => true,
//             'message' => 'Attendance marked successfully!',
//             'attendance_id' => $attendance->id
//         ]);
        
//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to mark attendance: ' . $e->getMessage()
//         ], 500);
// //     }
// }
 
// } 