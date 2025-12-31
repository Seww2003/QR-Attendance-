<?php
// app/Http/Controllers/QRCodeController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassSession;
use App\Models\Attendance;

class QRCodeController extends Controller
{
    // Show scanner page
    public function scanner()
    {
        return view('student.scanner');
    }

    // Process scanned QR code
    public function processScan(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        $qr_data = $request->qr_data;

        // Expecting format: ATTENDANCE:session_id:token
        $parts = explode(":", $qr_data);

        if(count($parts) !== 3 || $parts[0] !== 'ATTENDANCE') {
            return response()->json(['success' => false, 'message' => 'Invalid QR code format']);
        }

        $session_id = $parts[1];
        $token = $parts[2];

        // Find session
        $session = ClassSession::with('course.lecturer.user')->find($session_id);
        if(!$session) {
            return response()->json(['success' => false, 'message' => 'Session not found']);
        }

        // Validate token
        if($session->qr_token !== $token) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired QR code']);
        }

        // Success - return session details
        return response()->json([
            'success' => true,
            'session_id' => $session->id,
            'course_name' => $session->course->course_name,
            'topic' => $session->topic ?? 'General Session',
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
