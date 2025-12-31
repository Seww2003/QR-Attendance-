<?php
// app/Http/Controllers/LecturerController.php - COMPLETE WORKING VERSION

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ClassSession;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LecturerController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $lecturer = Auth::user()->lecturer;
        $courses = Course::where('lecturer_id', $lecturer->id)->count();
        $sessions = ClassSession::whereHas('course', function($q) use ($lecturer) {
            $q->where('lecturer_id', $lecturer->id);
        })->count();

        return view('lecturer.dashboard', compact('courses', 'sessions'));
    }

    // List courses
    public function courses()
    {
        $lecturer = Auth::user()->lecturer;
        $courses = Course::where('lecturer_id', $lecturer->id)->latest()->paginate(10);
        return view('lecturer.courses.index', compact('courses'));
    }

    // Show create course form
    public function createCourse()
    {
        return view('lecturer.courses.create');
    }

    // Store new course
    public function storeCourse(Request $request)
    {
        $request->validate([
            'course_code' => 'required|unique:courses',
            'course_name' => 'required',
            'description' => 'nullable',
        ]);

        $lecturer = Auth::user()->lecturer;

        Course::create([
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'description' => $request->description,
            'lecturer_id' => $lecturer->id,
        ]);

        return redirect()->route('lecturer.courses')->with('success', 'Course created successfully.');
    }

    // Show course details
    public function showCourse(Course $course)
    {
        // Get enrolled students
        $enrolledStudents = $course->students()->with('user')->paginate(10);
        
        // Get all students NOT enrolled in this course
        $allStudents = Student::whereNotIn('students.id', function($query) use ($course) {
            $query->select('course_student.student_id')
                  ->from('course_student')
                  ->where('course_student.course_id', $course->id);
        })->with('user')->get();
        
        // Get sessions
        $sessions = $course->sessions()->latest()->paginate(10);

        return view('lecturer.courses.show', compact('course', 'enrolledStudents', 'allStudents', 'sessions'));
    }

    // Enroll student
    public function enrollStudent(Request $request, Course $course)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $course->students()->attach($request->student_id, [
            'enrolled_date' => now(),
            'status' => 'enrolled'
        ]);

        return back()->with('success', 'Student enrolled successfully.');
    }

    // Show create session form
    public function createSession(Course $course)
    {
        return view('lecturer.sessions.create', compact('course'));
    }

    // Store new session
    public function storeSession(Request $request, Course $course)
    {
        $request->validate([
            'topic' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Create session using Eloquent
        $session = ClassSession::create([
            'course_id' => $course->id,
            'topic' => $request->topic,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_active' => true,
        ]);

        return redirect()->route('lecturer.courses.show', $course->id)
            ->with('success', 'Session created successfully! Session ID: ' . $session->id);
    }

    // ================ QR CODE GENERATION - SIMPLE WORKING VERSION ================
    public function generateQR($session)
    {
        try {
            // Debug: Check what $session contains
            \Log::info('QR Generation - Parameter received: ' . print_r($session, true));
            
            // If $session is a ClassSession model (route model binding), use it
            if ($session instanceof ClassSession) {
                $sessionObj = $session;
            } 
            // If $session is an ID (string/number), find the session
            else {
                $sessionObj = ClassSession::with('course')->find($session);
                
                if (!$sessionObj) {
                    return back()->with('error', 'Session not found! ID: ' . $session);
                }
            }
            
            \Log::info('Found Session: ID=' . $sessionObj->id . ', Topic=' . $sessionObj->topic);

            // Generate QR token if not exists
            if (!$sessionObj->qr_token) {
                $token = Str::uuid();
                $sessionObj->update([
                    'qr_token' => $token,
                    'qr_generated_at' => now()
                ]);
                $sessionObj->refresh();
            }

            // Create QR data (simple version)
            $qrData = "ATTENDANCE:" . $sessionObj->id . ":" . $sessionObj->qr_token;
            
            // Generate QR code
            $qrCode = QrCode::size(300)->generate($qrData);

            return view('lecturer.sessions.qr', [
                'session' => $sessionObj,
                'qrCode' => $qrCode
            ]);

        } catch (\Exception $e) {
            \Log::error('QR Generation Error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Error generating QR: ' . $e->getMessage());
        }
    }

    // Deactivate QR Code - SIMPLE VERSION
    public function deactivateQR($session)
    {
        try {
            // Find session
            $sessionObj = ($session instanceof ClassSession) ? $session : ClassSession::find($session);
            
            if (!$sessionObj) {
                return back()->with('error', 'Session not found');
            }

            $sessionObj->update([
                'qr_token' => null,
                'qr_generated_at' => null,
            ]);

            return back()->with('success', 'QR code deactivated successfully.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // View attendance - SIMPLE WORKING VERSION
    public function viewAttendance($session)
    {
        try {
            // Find session
            $sessionObj = ($session instanceof ClassSession) ? $session : ClassSession::find($session);
            
            if (!$sessionObj) {
                return back()->with('error', 'Session not found!');
            }

            // Load relationships
            $sessionObj->load(['course', 'attendances.student.user']);
            
            $attendances = $sessionObj->attendances;
            $enrolledStudents = $sessionObj->course->students()->with('user')->get();

            return view('lecturer.sessions.attendance', [
                'session' => $sessionObj,
                'attendances' => $attendances,
                'enrolledStudents' => $enrolledStudents
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Download attendance PDF - SIMPLE WORKING VERSION
    // Download attendance PDF - FIXED VERSION
public function downloadAttendancePDF($session)
{
    try {
        // Find session
        $sessionObj = ($session instanceof ClassSession) ? $session : ClassSession::find($session);
        
        if (!$sessionObj) {
            return back()->with('error', 'Session not found');
        }

        // Load relationships
        $sessionObj->load(['course.students.user', 'attendances.student.user']);
        $attendances = $sessionObj->attendances;
        
        // Get enrolled students - IMPORTANT
        $enrolledStudents = $sessionObj->course->students;

        // Check if PDF library is installed
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return back()->with('error', 'PDF library not installed. Please run: composer require barryvdh/laravel-dompdf');
        }

        // Generate PDF - PASS BOTH VARIABLES
        $pdf = Pdf::loadView('lecturer.sessions.attendance-pdf', [
            'session' => $sessionObj,
            'attendances' => $attendances,
            'enrolledStudents' => $enrolledStudents  // ADD THIS LINE
        ]);
        
        // Set filename
        $filename = 'attendance-' . $sessionObj->course->course_code . '-' . $sessionObj->date . '.pdf';
        
        return $pdf->download($filename);

    } catch (\Exception $e) {
        \Log::error('PDF Error: ' . $e->getMessage());
        return back()->with('error', 'PDF Error: ' . $e->getMessage());
    }
}
    // Activate Session - SIMPLE VERSION
    public function activateSession($session)
    {
        try {
            // Find session
            $sessionObj = ($session instanceof ClassSession) ? $session : ClassSession::find($session);
            
            if (!$sessionObj) {
                return back()->with('error', 'Session not found');
            }

            $sessionObj->update([
                'is_active' => true,
            ]);

            return back()->with('success', 'Session activated successfully.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

}