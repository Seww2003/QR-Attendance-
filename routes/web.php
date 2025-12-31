<?php
// routes/web.php - COMPLETE FIXED VERSION

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'lecturer') {
        return redirect()->route('lecturer.dashboard');
    } else {
        return redirect()->route('student.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ================== ADMIN ROUTES ==================
Route::prefix('admin')->name('admin.')->group(function () {
    // All admin routes require auth middleware
    Route::middleware(['auth'])->group(function () {
        
        // ================== DASHBOARD ==================
        Route::get('/dashboard', function() {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->dashboard();
        })->name('dashboard');
        
        // ================== STUDENTS MANAGEMENT ==================
        Route::get('/students', function() {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->students();
        })->name('students');
        
        Route::get('/students/create', function() {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->createStudent();
        })->name('students.create');
        
        Route::post('/students/store', function() {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->storeStudent(request());
        })->name('students.store');
        
        Route::get('/students/{id}', function($id) {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->showStudent($id);
        })->name('students.show');
        
        Route::get('/students/{id}/edit', function($id) {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->editStudent($id);
        })->name('students.edit');
        
       Route::put('/students/{id}', function($id) {
    if (auth()->user()->role !== 'admin') {
        return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
    }
    return app(AdminController::class)->updateStudent(request(), $id);
})->name('students.update');
        
        Route::delete('/students/{id}', function($id) {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->destroyStudent($id);
        })->name('students.destroy');
        
        Route::post('/students/{id}/resend-credentials', function($id) {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->resendCredentials($id);
        })->name('students.resend-credentials');
        
        // ================== LECTURERS MANAGEMENT ==================
        Route::get('/lecturers', function() {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->lecturers();
        })->name('lecturers');
        
        // LECTURERS CREATE ROUTE - FIXED
        Route::get('/lecturers/create', function() {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->createLecturer();
        })->name('lecturers.create');
        
        Route::post('/lecturers/store', function() {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->storeLecturer(request());
        })->name('lecturers.store');
        
        Route::get('/lecturers/{id}', function($id) {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->showLecturer($id);
        })->name('lecturers.show');
        // Add this route for resending credentials
Route::post('/lecturers/{id}/resend-credentials', function($id) {
    if (auth()->user()->role !== 'admin') {
        return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
    }
    return app(AdminController::class)->resendCredentials($id);
})->name('lecturers.resend-credentials');
        
        Route::get('/lecturers/{id}/edit', function($id) {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->editLecturer($id);
        })->name('lecturers.edit');
        
        Route::put('/lecturers/{id}', function($id) {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->updateLecturer(request(), $id);
        })->name('lecturers.update');
        
        Route::delete('/lecturers/{id}', function($id) {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->destroyLecturer($id);
        })->name('lecturers.destroy');
        
        // ================== COURSES MANAGEMENT ==================
        // ================== COURSES MANAGEMENT ==================
Route::get('/courses', function() {
    if (auth()->user()->role !== 'admin') {
        return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
    }
    return app(AdminController::class)->courses();
})->name('courses');

Route::get('/courses/create', function() {
    if (auth()->user()->role !== 'admin') {
        return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
    }
    
    // Get lecturers for dropdown
    $lecturers = \App\Models\Lecturer::with('user')->get();
    
    return view('admin.courses.create', compact('lecturers'));
})->name('courses.create');

Route::post('/courses/store', function() {
    if (auth()->user()->role !== 'admin') {
        return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
    }
    // You need to add storeCourse method to AdminController
    return app(AdminController::class)->storeCourse(request());
})->name('courses.store');

Route::get('/courses/{id}', function($id) {
    if (auth()->user()->role !== 'admin') {
        return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
    }
    return app(AdminController::class)->showCourse($id);
})->name('courses.show');

// ADD THESE MISSING ROUTES:
Route::get('/courses/{id}/edit', function($id) {
    if (auth()->user()->role !== 'admin') {
        return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
    }
    return app(AdminController::class)->editCourse($id);
})->name('courses.edit');

Route::put('/courses/{id}', function($id) {
    if (auth()->user()->role !== 'admin') {
        return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
    }
    return app(AdminController::class)->updateCourse(request(), $id);
})->name('courses.update');

Route::delete('/courses/{id}', function($id) {
    if (auth()->user()->role !== 'admin') {
        return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
    }
    return app(AdminController::class)->destroyCourse($id);
})->name('courses.destroy');

Route::get('/courses/{id}/edit', function($id) {
    if (auth()->user()->role !== 'admin') {
        return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
    }
    
    try {
        $course = \App\Models\Course::findOrFail($id);
        $lecturers = \App\Models\Lecturer::with('user')->get();
        
        return view('admin.courses.edit', compact('course', 'lecturers'));
        
    } catch (\Exception $e) {
        return redirect()->route('admin.courses')
            ->with('error', 'Course not found');
    }
})->name('courses.edit');
        
        // routes/web.php

  // ================== REPORTS ==================
        Route::get('/reports', function() {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->reports();
        })->name('reports');
        
        Route::post('/reports/generate', function() {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->generateReport(request());
        })->name('reports.generate');
        Route::get('/reports/download/{type}', function($type) {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->downloadReport($type);
        })->name('reports.download');
        
    
        
        // ================== SETTINGS ==================
        Route::get('/settings', function() {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->settings();
        })->name('settings');
        
        Route::post('/settings/update', function() {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->updateSettings(request());
        })->name('settings.update');
        
    }); // End of auth middleware group

        
        // ================== SETTINGS ==================
        Route::get('/settings', function() {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
            }
            return app(AdminController::class)->settings();
        })->name('settings');
    });


// ================== LECTURER ROUTES ==================
Route::middleware('auth')->prefix('lecturer')->name('lecturer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function() {
        if (auth()->user()->role !== 'lecturer') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(LecturerController::class)->dashboard();
    })->name('dashboard');
    
    // Courses Management
    Route::get('/courses', function() {
        if (auth()->user()->role !== 'lecturer') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(LecturerController::class)->courses();
    })->name('courses');
    
    Route::get('/courses/create', function() {
        if (auth()->user()->role !== 'lecturer') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(LecturerController::class)->createCourse();
    })->name('courses.create');
    
    Route::post('/courses', function() {
        if (auth()->user()->role !== 'lecturer') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(LecturerController::class)->storeCourse(request());
    })->name('courses.store');
    
    Route::get('/courses/{course}', function($course) {
        if (auth()->user()->role !== 'lecturer') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(LecturerController::class)->showCourse(\App\Models\Course::findOrFail($course));
    })->name('courses.show');
    
    Route::post('/courses/{course}/enroll', function($course) {
        if (auth()->user()->role !== 'lecturer') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(LecturerController::class)->enrollStudent(request(), \App\Models\Course::findOrFail($course));
    })->name('courses.enroll');
    
    // Sessions Management
    Route::get('/courses/{course}/sessions/create', function($course) {
        if (auth()->user()->role !== 'lecturer') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(LecturerController::class)->createSession(\App\Models\Course::findOrFail($course));
    })->name('sessions.create');
    
    Route::post('/courses/{course}/sessions', function($course) {
        if (auth()->user()->role !== 'lecturer') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(LecturerController::class)->storeSession(request(), \App\Models\Course::findOrFail($course));
    })->name('sessions.store');
    
    // QR Code Management
    Route::get('/sessions/{session}/qr', function($session) {
    if (auth()->user()->role !== 'lecturer') {
        return redirect('/dashboard')->with('error', 'Access denied');
    }
    return app(LecturerController::class)->generateQR(\App\Models\ClassSession::findOrFail($session));
})->name('sessions.qr');
    Route::put('/sessions/{session}/qr/deactivate', function($session) {
        if (auth()->user()->role !== 'lecturer') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(LecturerController::class)->deactivateQR(\App\Models\ClassSession::findOrFail($session));
    })->name('sessions.qr.deactivate');
    
    Route::post('/sessions/{session}/qr/generate', function($session) {
        if (auth()->user()->role !== 'lecturer') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(LecturerController::class)->generateQR(\App\Models\ClassSession::findOrFail($session));
    })->name('sessions.qr.generate');
    
    // Attendance Management
    Route::get('/sessions/{session}/attendance', function($session) {
        if (auth()->user()->role !== 'lecturer') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(LecturerController::class)->viewAttendance(\App\Models\ClassSession::findOrFail($session));
    })->name('sessions.attendance');
    
    Route::get('/sessions/{session}/attendance/pdf', function($session) {
        if (auth()->user()->role !== 'lecturer') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(LecturerController::class)->downloadAttendancePDF(\App\Models\ClassSession::findOrFail($session));
    })->name('sessions.attendance.pdf');
    
    // Session Activation
    Route::put('/sessions/{session}/activate', function($session) {
        if (auth()->user()->role !== 'lecturer') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(LecturerController::class)->activateSession(\App\Models\ClassSession::findOrFail($session));
    })->name('sessions.activate');

});

// ================== STUDENT ROUTES ==================
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function() {
        if (auth()->user()->role !== 'student') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(StudentController::class)->dashboard();
    })->name('dashboard');
    
    // Courses
    Route::get('/courses', function() {
        if (auth()->user()->role !== 'student') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(StudentController::class)->myCourses();
    })->name('courses');
    
    Route::get('/courses/{course}', function($course) {
        if (auth()->user()->role !== 'student') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(StudentController::class)->showCourse($course);
    })->name('courses.show');
    
    // Attendance
    Route::get('/attendance', function() {
        if (auth()->user()->role !== 'student') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(AttendanceController::class)->myAttendance();
    })->name('attendance');
    
    // Profile
    Route::get('/profile', function() {
        if (auth()->user()->role !== 'student') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return redirect()->route('profile.edit');
    })->name('profile');
});

// ================== QR CODE & ATTENDANCE ROUTES ==================
Route::middleware(['auth'])->group(function () {
    // QR Scanner
    Route::get('/qr-scanner', function() {
        if (auth()->user()->role !== 'student') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(QRCodeController::class)->scanner();
    })->name('qr.scanner');
    
    // Process QR Scan
    Route::post('/qr-scan', function() {
        if (auth()->user()->role !== 'student') {
            return response()->json(['success' => false, 'message' => 'Access denied']);
        }
        return app(QRCodeController::class)->processScan(request());
    })->name('qr.process');
    
    // Mark Attendance
    Route::post('/mark-attendance', function() {
        if (auth()->user()->role !== 'student') {
            return response()->json(['success' => false, 'message' => 'Access denied']);
        }
        return app(AttendanceController::class)->markAttendance(request());
    })->name('attendance.mark');
    
    // View Attendance History
    Route::get('/my-attendance', function() {
        if (auth()->user()->role !== 'student') {
            return redirect('/dashboard')->with('error', 'Access denied');
        }
        return app(AttendanceController::class)->myAttendanceHistory();
    })->name('attendance.history');
});

// ================== PUBLIC/UTILITY ROUTES ==================
Route::get('/check-sessions', function() {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    $sessions = \DB::table('class_sessions')->get();
    
    if ($sessions->isEmpty()) {
        return "No sessions found!";
    }
    
    $html = "<h2>Sessions:</h2>";
    foreach ($sessions as $session) {
        $html .= "<p>ID: {$session->id} - Topic: {$session->topic} - Course ID: {$session->course_id}</p>";
        $html .= "<a href='/lecturer/sessions/{$session->id}/qr'>Generate QR</a><hr>";
    }
    return $html;
});

// Test route for admin sidebar
Route::get('/test-admin-sidebar', function() {
    if (auth()->user()->role !== 'admin') {
        return redirect('/dashboard');
    }
    
    // Test all admin routes
    $routes = [
        'admin.dashboard' => route('admin.dashboard'),
        'admin.students' => route('admin.students'),
        'admin.lecturers' => route('admin.lecturers'),
        'admin.reports' => route('admin.reports'),
        'admin.students.create' => route('admin.students.create'),
    ];
    
    return view('test-routes', compact('routes'));
})->middleware('auth');

require __DIR__.'/auth.php';