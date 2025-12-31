{{-- resources/views/lecturer/courses/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Course Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">{{ $course->course_code }} - {{ $course->course_name }}</h2>
                    <p class="text-muted mb-0">{{ $course->description }}</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-info">{{ $course->students->count() }} Students</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Students Section -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Enrolled Students</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#enrollModal">
                        <i class="fas fa-plus"></i> Enroll Student
                    </button>
                </div>
                <div class="card-body">
                    @if($enrolledStudents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Enrolled Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrolledStudents as $student)
                                    <tr>
                                        <td>{{ $student->student_id }}</td>
                                        <td>{{ $student->user->name }}</td>
                                        <td>{{ $student->pivot->enrolled_date }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $enrolledStudents->links() }}
                        </div>
                    @else
                        <p class="text-center text-muted py-3">No students enrolled yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sessions Section -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Class Sessions</h5>
                    <a href="{{ route('lecturer.sessions.create', $course) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> New Session
                    </a>
                </div>
                <div class="card-body">
                    @if($sessions->count() > 0)
                        <div class="list-group">
                            @foreach($sessions as $session)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $session->topic ?: 'Session' }}</h6>
                                        <small class="text-muted">
                                            {{ $session->date }} | {{ date('h:i A', strtotime($session->start_time)) }} - {{ date('h:i A', strtotime($session->end_time)) }}
                                        </small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('lecturer.sessions.qr', $session) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                        <a href="{{ route('lecturer.sessions.attendance', $session) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-list"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        {{ $sessions->links() }}
                    @else
                        <p class="text-center text-muted py-3">No sessions created yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enroll Student Modal -->
<div class="modal fade" id="enrollModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enroll Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('lecturer.courses.enroll', $course) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Select Student</label>
                        <select class="form-select" id="student_id" name="student_id" required>
                            <option value="">-- Select Student --</option>
                            @foreach($allStudents as $student)
                                <option value="{{ $student->id }}">{{ $student->student_id }} - {{ $student->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Enroll Student</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection