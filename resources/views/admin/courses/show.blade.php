@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Course Details</h1>
        <a href="{{ route('admin.courses') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Courses
        </a>
    </div>

    <div class="row">
        <!-- Course Information -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Course Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Course Code:</th>
                            <td>{{ $course->course_code }}</td>
                        </tr>
                        <tr>
                            <th>Course Name:</th>
                            <td>{{ $course->name ?? $course->course_name }}</td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $course->description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Lecturer:</th>
                            <td>
                                @if($course->lecturer)
                                    {{ $course->lecturer->user->name }}
                                    <small class="text-muted">({{ $course->lecturer->employee_id }})</small>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Credit Hours:</th>
                            <td>{{ $course->credit_hours ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $course->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At:</th>
                            <td>{{ $course->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Course
                        </a>
                        <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this course?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Course
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrolled Students -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Enrolled Students</h6>
                    <span class="badge badge-primary">{{ $course->students->count() }} Students</span>
                </div>
                <div class="card-body">
                    @if($course->students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($course->students as $student)
                                    <tr>
                                        <td>{{ $student->student_id }}</td>
                                        <td>{{ $student->user->name }}</td>
                                        <td>{{ $student->user->email }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No students enrolled in this course.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection