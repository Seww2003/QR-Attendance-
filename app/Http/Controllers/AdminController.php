<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Course;
use App\Models\ClassSession;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    // ================== DASHBOARD ==================
    public function dashboard()
    {
        $stats = [
            'totalStudents' => Student::count(),
            'totalLecturers' => Lecturer::count(),
            'totalCourses' => Course::count(),
            'totalSessions' => ClassSession::count(),
            'totalAttendance' => Attendance::count(),
            'todayAttendance' => Attendance::whereDate('created_at', today())->count(),
            'activeSessions' => ClassSession::where('is_active', true)->count(),
        ];

        $recentStudents = Student::with('user')->latest()->take(5)->get();
        $recentLecturers = Lecturer::with('user')->latest()->take(5)->get();
        $recentCourses = Course::with('lecturer.user')->latest()->take(5)->get();
        $recentAttendance = Attendance::with(['session.course', 'student.user'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentStudents', 'recentLecturers', 'recentCourses', 'recentAttendance'));
    }

    // ================== STUDENTS MANAGEMENT ==================
    
    public function students()
    {
        $students = Student::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.students.index', compact('students'));
    }
    
    public function createStudent()
    {
        return view('admin.students.create');
    }
    
    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nic' => 'required|string|max:15',
            'student_id' => 'required|string|max:50|unique:students',
            'phone' => 'nullable|string|max:20',
            'batch' => 'nullable|string|max:20',
            'faculty' => 'nullable|string|max:100',
        ]);
        
        $tempPassword = Str::random(8);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($tempPassword),
            'temp_password' => $tempPassword,
            'password_changed' => false,
            'role' => 'student',
            'nic' => $request->nic,
        ]);
        
        $student = Student::create([
            'user_id' => $user->id,
            'student_id' => $request->student_id,
            'phone' => $request->phone,
            'batch' => $request->batch,
            'faculty' => $request->faculty,
        ]);

        return redirect()->route('admin.students')
            ->with('success', 'Student created successfully. Temporary password: ' . $tempPassword);
    }

    public function showStudent($id)
    {
        $student = Student::with(['user', 'courses', 'attendances.session.course'])->findOrFail($id);
        return view('admin.students.show', compact('student'));
    }

    public function resendCredentials($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->temp_password) {
            $tempPassword = $user->temp_password;
        } else {
            $tempPassword = Str::random(8);
            $user->update([
                'password' => Hash::make($tempPassword),
                'temp_password' => $tempPassword,
            ]);
        }

        return back()->with('success', 'Password reset. New password: ' . $tempPassword);
    }

    public function editStudent($id)
    {
        $student = Student::with('user')->findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }
    
public function updateStudent(Request $request, $id)
{
    // Log the request
    \Log::info('=== UPDATE STUDENT START ===');
    \Log::info('Student ID: ' . $id);
    \Log::info('Form Data:', $request->all());
    
    try {
        // Find the student
        $student = Student::with('user')->find($id);
        
        if (!$student) {
            \Log::error('Student not found: ' . $id);
            return redirect()->route('admin.students')->with('error', 'Student not found');
        }
        
        \Log::info('Found Student:', [
            'student_id' => $student->student_id,
            'user_id' => $student->user_id,
            'user_name' => $student->user->name,
            'user_email' => $student->user->email
        ]);
        
        // SIMPLE VALIDATION - Remove unique constraints temporarily
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'nic' => 'required|string|max:15',
            'student_id' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'batch' => 'nullable|string|max:20',
            'faculty' => 'nullable|string|max:100',
        ]);
        
        \Log::info('Validation passed');
        
        // MANUAL UNIQUE CHECKS
        // Check email uniqueness
        $emailExists = \App\Models\User::where('email', $request->email)
            ->where('id', '!=', $student->user_id)
            ->exists();
            
        if ($emailExists) {
            \Log::warning('Email already exists: ' . $request->email);
            return back()->withInput()->with('error', 'Email already taken by another user');
        }
        
        // Check student_id uniqueness
        $studentIdExists = Student::where('student_id', $request->student_id)
            ->where('id', '!=', $id)
            ->exists();
            
        if ($studentIdExists) {
            \Log::warning('Student ID already exists: ' . $request->student_id);
            return back()->withInput()->with('error', 'Student ID already taken by another student');
        }
        
        \Log::info('Uniqueness checks passed');
        
        // UPDATE USER
        $userUpdateData = [
            'name' => $request->name,
            'email' => $request->email,
            'nic' => $request->nic,
            'updated_at' => now()
        ];
        
        \Log::info('Updating user with data:', $userUpdateData);
        
        $userUpdated = $student->user->update($userUpdateData);
        
        \Log::info('User update result: ' . ($userUpdated ? 'success' : 'failed'));
        
        // UPDATE STUDENT
        $studentUpdateData = [
            'student_id' => $request->student_id,
            'phone' => $request->phone,
            'batch' => $request->batch,
            'faculty' => $request->faculty,
            'updated_at' => now()
        ];
        
        \Log::info('Updating student with data:', $studentUpdateData);
        
        $studentUpdated = $student->update($studentUpdateData);
        
        \Log::info('Student update result: ' . ($studentUpdated ? 'success' : 'failed'));
        
        // Refresh data
        $student->refresh();
        $student->load('user');
        
        \Log::info('After update:', [
            'new_name' => $student->user->name,
            'new_email' => $student->user->email,
            'new_student_id' => $student->student_id
        ]);
        
        \Log::info('=== UPDATE STUDENT END ===');
        
        return redirect()->route('admin.students')
            ->with('success', 'Student "' . $request->name . '" updated successfully!');
            
    } catch (\Exception $e) {
        \Log::error('Update failed: ' . $e->getMessage());
        \Log::error('Trace: ' . $e->getTraceAsString());
        
        return back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
    }
}

    // ================== LECTURERS MANAGEMENT ==================
    public function lecturers()
    {
        $lecturers = Lecturer::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.lecturers.index', compact('lecturers'));
    }

    public function createLecturer()
    {
        return view('admin.lecturers.create');
    }
    

    public function storeLecturer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nic' => 'required|string|max:15',
            'employee_id' => 'required|unique:lecturers,employee_id',
            'department' => 'nullable|string|max:100',
            'qualification' => 'nullable|string|max:255',
        ]);

        $tempPassword = Str::random(8);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($tempPassword),
            'temp_password' => $tempPassword,
            'password_changed' => false,
            'role' => 'lecturer',
            'nic' => $request->nic,
        ]);

        $lecturer = Lecturer::create([
            'user_id' => $user->id,
            'employee_id' => $request->employee_id,
            'department' => $request->department,
            'qualification' => $request->qualification,
        ]);

        return redirect()->route('admin.lecturers')
            ->with('success', 'Lecturer created successfully. Temporary password: ' . $tempPassword);
    }

   public function showLecturer($id)
{
    try {
        // Try to load with relationships
        $lecturer = Lecturer::with(['user', 'courses.students'])->findOrFail($id);
    } catch (\Exception $e) {
        // Fallback to simple query
        $lecturer = Lecturer::with('user')->findOrFail($id);
    }
    
    return view('admin.lecturers.show', compact('lecturer'));
}

    public function editLecturer($id)
    {
        $lecturer = Lecturer::with('user')->findOrFail($id);
        return view('admin.lecturers.edit', compact('lecturer'));
    }

    public function updateLecturer(Request $request, $id)
    {
        $lecturer = Lecturer::with('user')->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $lecturer->user_id,
            'nic' => 'required|string|max:15',
            'employee_id' => 'required|string|max:50|unique:lecturers,employee_id,' . $id,
            'department' => 'nullable|string|max:100',
            'qualification' => 'nullable|string|max:255',
        ]);
        
        $lecturer->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nic' => $request->nic,
        ]);
        
        $lecturer->update([
            'employee_id' => $request->employee_id,
            'department' => $request->department,
            'qualification' => $request->qualification,
        ]);
        
        return redirect()->route('admin.lecturers')->with('success', 'Lecturer updated successfully.');
    }

    public function destroyLecturer($id)
    {
        $lecturer = Lecturer::findOrFail($id);
        $userId = $lecturer->user_id;
        
        $lecturer->delete();
        User::find($userId)->delete();
        
        return redirect()->route('admin.lecturers')->with('success', 'Lecturer deleted successfully.');
    }

    // ================== COURSES MANAGEMENT ==================
    public function courses()
{
    try {
        $courses = Course::with(['lecturer.user', 'students'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.courses.index', compact('courses'));
    } catch (\Exception $e) {
        // Fallback if view doesn't exist
        return response()->json([
            'courses' => Course::with(['lecturer.user', 'students'])->paginate(10),
            'message' => 'Courses view not found. Please create admin/courses/index.blade.php'
        ]);
    }
}
/**
 * Store new course
 */
public function storeCourse(Request $request)
{
    \Log::info('=== CREATE COURSE ===');
    \Log::info('Form Data:', $request->all());
    
    try {
        // Check database column name
        \Log::info('Checking database columns...');
        
        // SIMPLE VALIDATION
        $validated = $request->validate([
            'course_code' => 'required|string|max:50',
            'name' => 'required|string|max:255', // This should match form field name
            'description' => 'nullable|string',
            'credit_hours' => 'nullable|integer|min:1',
        ]);
        
        \Log::info('Validation passed');
        
        // Check if course_code already exists
        $courseCodeExists = \App\Models\Course::where('course_code', $request->course_code)->exists();
        if ($courseCodeExists) {
            return back()->withInput()->with('error', 'Course code already exists');
        }
        
        // Prepare course data - CHECK COLUMN NAMES
        $courseData = [
            'course_code' => $request->course_code,
            'course_name' => $request->name, // Use 'course_name' if that's the column name
            // OR if column is 'name', use:
            // 'name' => $request->name,
            'description' => $request->description,
            'credit_hours' => $request->credit_hours,
        ];
        
        // Add lecturer_id if provided
        if ($request->filled('lecturer_id')) {
            $lecturerExists = \App\Models\Lecturer::where('id', $request->lecturer_id)->exists();
            if ($lecturerExists) {
                $courseData['lecturer_id'] = $request->lecturer_id;
            }
        }
        
        \Log::info('Creating course with data:', $courseData);
        
        // Create course
        $course = Course::create($courseData);
        
        \Log::info('Course created successfully:', ['id' => $course->id]);
        
        return redirect()->route('admin.courses')
            ->with('success', 'Course "' . $request->name . '" created successfully!');
            
    } catch (\Exception $e) {
        \Log::error('Error: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Failed to create course: ' . $e->getMessage());
    }
}

/**
 * Display course details
 */
public function showCourse($id)
{
    \Log::info('=== SHOW COURSE ===');
    \Log::info('Course ID: ' . $id);
    
    try {
        // Load course with relationships
        $course = Course::with([
            'lecturer.user', 
            'students.user'
        ])->findOrFail($id);
        
        \Log::info('Course found:', ['name' => $course->name ?? $course->course_name]);
        
        return view('admin.courses.show', compact('course'));
        
    } catch (\Exception $e) {
        \Log::error('Show course error: ' . $e->getMessage());
        return redirect()->route('admin.courses')
            ->with('error', 'Course not found: ' . $e->getMessage());
    }
}

/**
 * Show edit course form
 */
public function editCourse($id)
{
    \Log::info('=== EDIT COURSE ===');
    \Log::info('Course ID: ' . $id);
    
    try {
        // Load course with relationships
        $course = Course::with(['lecturer.user', 'students'])
            ->findOrFail($id);
        
        // Get all lecturers for dropdown
        $lecturers = Lecturer::with('user')->get();
        
        \Log::info('Course found for editing:', ['name' => $course->name ?? $course->course_name]);
        
        return view('admin.courses.edit', compact('course', 'lecturers'));
        
    } catch (\Exception $e) {
        \Log::error('Edit course error: ' . $e->getMessage());
        return redirect()->route('admin.courses')
            ->with('error', 'Course not found: ' . $e->getMessage());
    }
}
/**
 * Update course
 */
public function updateCourse(Request $request, $id)
{
    \Log::info('=== UPDATE COURSE ===');
    \Log::info('Course ID: ' . $id);
    \Log::info('Request Data:', $request->all());
    
    try {
        $course = Course::findOrFail($id);
        
        // Check database column name for course name
        $columns = \DB::select('DESCRIBE courses');
        $columnNames = array_column($columns, 'Field');
        $nameColumn = in_array('course_name', $columnNames) ? 'course_name' : 'name';
        \Log::info('Using column name for course name: ' . $nameColumn);
        
        // Validate request
        $request->validate([
            'course_code' => 'required|string|max:50|unique:courses,course_code,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lecturer_id' => 'nullable|exists:lecturers,id',
            'credit_hours' => 'nullable|integer|min:1',
        ]);
        
        \Log::info('Validation passed');
        
        // Prepare update data
        $updateData = [
            'course_code' => $request->course_code,
            $nameColumn => $request->name, // Use correct column name
            'description' => $request->description,
            'credit_hours' => $request->credit_hours,
        ];
        
        // Add lecturer_id if provided, otherwise set to null
        if ($request->filled('lecturer_id')) {
            $updateData['lecturer_id'] = $request->lecturer_id;
        } else {
            $updateData['lecturer_id'] = null;
        }
        
        \Log::info('Updating course with data:', $updateData);
        
        // Update course
        $course->update($updateData);
        
        \Log::info('Course updated successfully');
        
        return redirect()->route('admin.courses')
            ->with('success', 'Course updated successfully!');
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation error: ', $e->errors());
        return back()->withErrors($e->validator)->withInput();
    } catch (\Exception $e) {
        \Log::error('Update course error: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Failed to update course: ' . $e->getMessage());
    }
}
    // ================== REPORTS ==================
    public function reports()
    {
        $stats = [
            'totalStudents' => Student::count(),
            'totalLecturers' => Lecturer::count(),
            'totalCourses' => Course::count(),
            'totalSessions' => ClassSession::count(),
            'totalAttendance' => Attendance::count(),
        ];

        // Last 7 days attendance
        $attendanceByDay = Attendance::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top courses by attendance - FIXED VERSION
       $topCourses = Course::withCount('attendances')
    ->orderBy('attendances_count', 'desc')
    ->take(5)
    ->get();


        // Top students by attendance
        $topStudents = Student::withCount('attendances')
            ->orderBy('attendances_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact('stats', 'attendanceByDay', 'topCourses', 'topStudents'));
    }

    // Report generation method
   public function generateReport(Request $request)
{
    $request->validate([
        'report_type' => 'required|in:students,lecturers,courses,attendance',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'format' => 'nullable|in:html,pdf,csv,excel'
    ]);

    $reportType = $request->input('report_type');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $format = $request->input('format', 'html');

    switch ($reportType) {
        case 'students':
            $data = Student::with(['user', 'courses'])->get();
            $title = 'Students Report';
            $filename = 'students_report_' . date('Y-m-d');
            break;
            
        case 'lecturers':
            $data = Lecturer::with(['user', 'courses'])->get();
            $title = 'Lecturers Report';
            $filename = 'lecturers_report_' . date('Y-m-d');
            break;
            
        case 'courses':
            $data = Course::with(['lecturer.user', 'students'])->get();
            $title = 'Courses Report';
            $filename = 'courses_report_' . date('Y-m-d');
            break;
            
        case 'attendance':
            $query = Attendance::with(['student.user', 'session.course']);
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            $data = $query->get();
            $title = 'Attendance Report';
            $filename = 'attendance_report_' . date('Y-m-d');
            break;
            
        default:
            return redirect()->back()->with('error', 'Invalid report type');
    }

    // Check format and return accordingly
    if ($format === 'pdf') {
    // Define columns based on report type
    $columns = [];
    switch ($reportType) {
        case 'students':
            $columns = ['Student ID', 'Name', 'Email', 'NIC', 'Phone', 'Courses', 'Created At'];
            break;
        case 'lecturers':
            $columns = ['Employee ID', 'Name', 'Email', 'Department', 'Qualification', 'Courses'];
            break;
        case 'courses':
            $columns = ['Course Code', 'Course Name', 'Lecturer', 'Description', 'Students', 'Created At'];
            break;
        case 'attendance':
            $columns = ['Student', 'Course', 'Session', 'Attended At', 'Status', 'Remarks'];
            break;
    }
    
    // Generate PDF with all required variables
    $pdf = Pdf::loadView('admin.reports.pdf', compact('data', 'title', 'columns', 'reportType', 'startDate', 'endDate'));
    return $pdf->download($filename . '.pdf');
}
}

// Helper methods for different formats
public function downloadReport($type)
{
    // Admin check
    if (auth()->user()->role !== 'admin') {
        abort(403, 'Access denied. Admin only.');
    }
    
    $startDate = request('start_date');
    $endDate = request('end_date');
    
    switch ($type) {
        case 'students':
            $data = Student::with(['user', 'courses'])->get();
            $title = 'Students Report';
            $filename = 'students_report_' . date('Y-m-d') . '.pdf';
            $columns = ['Student ID', 'Name', 'Email', 'NIC', 'Phone', 'Courses', 'Created At'];
            break;
            
        case 'lecturers':
            $data = Lecturer::with(['user', 'courses'])->get();
            $title = 'Lecturers Report';
            $filename = 'lecturers_report_' . date('Y-m-d') . '.pdf';
            $columns = ['Employee ID', 'Name', 'Email', 'Department', 'Qualification', 'Courses'];
            break;
            
        case 'courses':
            $data = Course::with(['lecturer.user', 'students'])->get();
            $title = 'Courses Report';
            $filename = 'courses_report_' . date('Y-m-d') . '.pdf';
            $columns = ['Course Code', 'Course Name', 'Lecturer', 'Description', 'Students', 'Created At'];
            break;
            
        case 'attendance':
            $query = Attendance::with(['student.user', 'session.course']);
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            $data = $query->get();
            $title = 'Attendance Report';
            $filename = 'attendance_report_' . date('Y-m-d') . '.pdf';
            $columns = ['Student', 'Course', 'Session', 'Attended At', 'Status', 'Remarks'];
            break;
            
        default:
            return redirect()->route('admin.reports')->with('error', 'Invalid report type');
    }
    
    // Generate PDF
    $pdf = Pdf::loadView('admin.reports.pdf', compact('data', 'title', 'columns', 'type', 'startDate', 'endDate'));
    
    // Download the PDF
    return $pdf->download($filename);
}
       
    

    // ================== SYSTEM SETTINGS ==================
    public function settings()
    {
        return view('admin.settings.index');
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_email' => 'nullable|email',
            'attendance_timeout' => 'nullable|integer|min:1',
            'qr_validity_minutes' => 'nullable|integer|min:1',
        ]);

        return back()->with('success', 'Settings updated successfully.');
    }
}