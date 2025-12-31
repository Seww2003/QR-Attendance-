{{-- resources/views/student/attendance.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">My Attendance</h1>

    <!-- Attendance Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <h5>Total Attendance</h5>
                    <h2>{{ $attendances->total() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card">
        <div class="card-body">
            @if($attendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Course</th>
                                <th>Session Time</th>
                                <th>Your Attendance Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->session->date }}</td>
                                <td>
                                    <strong>{{ $attendance->session->course->course_code }}</strong><br>
                                    <small>{{ $attendance->session->course->course_name }}</small>
                                </td>
                                <td>
                                    {{ date('h:i A', strtotime($attendance->session->start_time)) }} - 
                                    {{ date('h:i A', strtotime($attendance->session->end_time)) }}
                                </td>
                                <td>{{ $attendance->attended_at->format('h:i A') }}</td>
                                <td>
                                    <span class="badge bg-success">{{ ucfirst($attendance->status) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $attendances->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                    <h5>No attendance records</h5>
                    <p class="text-muted">You haven't attended any sessions yet.</p>
                    <a href="{{ route('qr.scanner') }}" class="btn btn-primary">
                        <i class="fas fa-qrcode"></i> Scan QR Code
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
