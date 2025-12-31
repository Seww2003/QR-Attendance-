@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">System Reports</h1>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        @foreach($stats as $key => $value)
        <div class="col-md-2 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        {{ str_replace('_', ' ', ucfirst($key)) }}
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $value }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Report Generation Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Generate Report</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reports.generate') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Report Type</label>
                <select name="report_type" class="form-control" required>
                    <option value="">-- Select Report Type --</option>
                    <option value="students" {{ old('report_type') == 'students' ? 'selected' : '' }}>Students Report</option>
                    <option value="lecturers" {{ old('report_type') == 'lecturers' ? 'selected' : '' }}>Lecturers Report</option>
                    <option value="courses" {{ old('report_type') == 'courses' ? 'selected' : '' }}>Courses Report</option>
                    <option value="attendance" {{ old('report_type') == 'attendance' ? 'selected' : '' }}>Attendance Report</option>
                </select>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label>Start Date (Optional)</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label>End Date (Optional)</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="form-group">
                <label>Output Format</label>
                <select name="format" class="form-control" required>
                    <option value="html" selected>View in Browser</option>
                    <option value="pdf">Download PDF</option>
                    
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-play-circle"></i> Generate Report
            </button>
            <button type="reset" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </button>
        </div>
    </div>
</form>

<!-- Error messages display -->
@if(session('error'))
<div class="alert alert-danger mt-3">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="alert alert-success mt-3">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
        </div>
    </div>

    <!-- Top Courses -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Courses by Attendance</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Lecturer</th>
                                    <th>Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCourses as $course)
                                <tr>
                                    <td>{{ $course->course_code }}</td>
                                    <td>{{ $course->lecturer->user->name ?? 'N/A' }}</td>
                                    <td>{{ $course->attendances_count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Students by Attendance</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topStudents as $student)
                                <tr>
                                    <td>{{ $student->student_id }}</td>
                                    <td>{{ $student->user->name }}</td>
                                    <td>{{ $student->attendances_count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection