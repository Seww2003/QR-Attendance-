{{-- resources/views/student/course-show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>{{ $course->course_code }} - {{ $course->course_name }}</h3>
            <p class="mb-0">{{ $course->description }}</p>
            <small class="text-muted">Lecturer: {{ $course->lecturer->user->name }}</small>
        </div>
        <div class="card-body">
            <h5>Class Sessions</h5>
            
            @if($sessions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Topic</th>
                                <th>Your Attendance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessions as $session)
                            <tr>
                                <td>{{ $session->date }}</td>
                                <td>
                                    {{ date('h:i A', strtotime($session->start_time)) }} - 
                                    {{ date('h:i A', strtotime($session->end_time)) }}
                                </td>
                                <td>{{ $session->topic ?: 'N/A' }}</td>
                                <td>
                                    @if($session->attended_at)
                                        {{ date('h:i A', strtotime($session->attended_at)) }}
                                    @else
                                        <span class="text-muted">Not attended</span>
                                    @endif
                                </td>
                                <td>
                                    @if($session->attended_at)
                                        <span class="badge bg-success">Present</span>
                                    @else
                                        <span class="badge bg-danger">Absent</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $sessions->links() }}
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    No sessions scheduled for this course yet.
                </div>
            @endif
            
            <div class="mt-3">
                <a href="{{ route('student.courses') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Courses
                </a>
            </div>
        </div>
    </div>
</div>
@endsection