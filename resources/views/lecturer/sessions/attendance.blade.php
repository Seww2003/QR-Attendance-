@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- CARD HEADER WITH PDF BUTTON -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Attendance for Session</h4>
                    <div>
                        <a href="{{ route('lecturer.courses.show', $session->course_id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Course
                        </a>
                        <a href="{{ route('lecturer.sessions.qr', $session->id) }}" class="btn btn-primary btn-sm ms-2">
                            <i class="fas fa-qr-code"></i> QR Code
                        </a>
                        <!-- PDF DOWNLOAD BUTTON - ADD THIS LINE -->
                        <a href="{{ route('lecturer.sessions.attendance.pdf', $session->id) }}" class="btn btn-success btn-sm ms-2">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                    </div>
                </div>
                <!-- END CARD HEADER -->

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Session Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Session Details</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Course</th>
                                    <td>{{ $session->course->course_code }} - {{ $session->course->course_name }}</td>
                                </tr>
                                <tr>
                                    <th>Topic</th>
                                    <td>{{ $session->topic ?? 'No topic specified' }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ \Carbon\Carbon::parse($session->date)->format('F j, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Time</th>
                                    <td>{{ date('h:i A', strtotime($session->start_time)) }} - {{ date('h:i A', strtotime($session->end_time)) }}</td>
                                </tr>
                                <tr>
                                    <th>QR Status</th>
                                    <td>
                                        @if($session->qr_token)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Not Generated</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Attendance Summary</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Total Enrolled</th>
                                    <td>{{ $enrolledStudents->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Present</th>
                                    <td class="text-success">{{ $attendances->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Absent</th>
                                    <td class="text-danger">{{ $enrolledStudents->count() - $attendances->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Attendance Rate</th>
                                    <td>
                                        @if($enrolledStudents->count() > 0)
                                            {{ round(($attendances->count() / $enrolledStudents->count()) * 100, 2) }}%
                                        @else
                                            0%
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Present Students -->
                    <h5>Present Students ({{ $attendances->count() }})</h5>
                    @if($attendances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Scanned Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $index => $attendance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $attendance->student->student_id ?? 'N/A' }}</td>
                                        <td>{{ $attendance->student->user->name ?? 'N/A' }}</td>
                                        <td>{{ $attendance->scanned_at ? \Carbon\Carbon::parse($attendance->scanned_at)->format('Y-m-d h:i A') : 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-success">Present</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> No attendance records found for this session.
                        </div>
                    @endif

                    <!-- Absent Students -->
                    @if($enrolledStudents->count() > 0)
                        <h5 class="mt-4">Absent Students ({{ $enrolledStudents->count() - $attendances->count() }})</h5>
                        @php
                            $presentStudentIds = $attendances->pluck('student_id')->toArray();
                            $absentStudents = $enrolledStudents->whereNotIn('id', $presentStudentIds);
                        @endphp
                        
                        @if($absentStudents->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($absentStudents as $index => $student)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $student->student_id ?? 'N/A' }}</td>
                                            <td>{{ $student->user->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-danger">Absent</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> All enrolled students are present!
                            </div>
                        @endif
                    @endif
                    
                    <!-- ADDITIONAL PDF BUTTON AT BOTTOM (OPTIONAL) -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('lecturer.sessions.attendance.pdf', $session->id) }}" class="btn btn-success">
                                <i class="fas fa-download me-2"></i> Download Attendance Report as PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection