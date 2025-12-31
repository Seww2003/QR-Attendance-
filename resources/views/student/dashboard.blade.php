{{-- resources/views/student/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Student Dashboard</h1>
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Enrolled Courses</h5>
                            <h2 class="mb-0">{{ $courses }}</h2>
                        </div>
                        <i class="fas fa-book fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Attendance Recorded</h5>
                            <h2 class="mb-0">{{ $attendance }}</h2>
                        </div>
                        <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Quick Actions</h5>
                            <div class="mt-2">
                                <a href="{{ route('qr.scanner') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-qrcode"></i> Scan QR
                                </a>
                            </div>
                        </div>
                        <i class="fas fa-bolt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Attendance</h5>
                </div>
                <div class="card-body">
                    @php
                        $recentAttendance = Auth::user()->student->attendances()
                            ->with('session.course')
                            ->latest()
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @if($recentAttendance->count() > 0)
                        <div class="list-group">
                            @foreach($recentAttendance as $attendance)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $attendance->session->course->course_name }}</h6>
                                        <small class="text-muted">{{ $attendance->attended_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                    <span class="badge bg-success">{{ $attendance->status }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted">No attendance records yet</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">My Courses</h5>
                </div>
                <div class="card-body">
                    @php
                        $myCourses = Auth::user()->student->courses()->take(5)->get();
                    @endphp
                    
                    @if($myCourses->count() > 0)
                        <div class="list-group">
                            @foreach($myCourses as $course)
                            <a href="{{ route('student.courses.show', $course) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $course->course_code }}</h6>
                                        <small class="text-muted">{{ $course->course_name }}</small>
                                    </div>
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted">No courses enrolled yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection