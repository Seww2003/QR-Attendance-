{{-- resources/views/lecturer/courses/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">My Courses</h1>
        <a href="{{ route('lecturer.courses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Course
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($courses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Description</th>
                                <th>Enrolled Students</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td>{{ $course->course_code }}</td>
                                <td>{{ $course->course_name }}</td>
                                <td>{{ Str::limit($course->description, 50) }}</td>
                                <td>{{ $course->students->count() }}</td>
                                <td>
                                    <a href="{{ route('lecturer.courses.show', $course) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $courses->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5>No courses found</h5>
                    <p class="text-muted">Create your first course to get started</p>
                    <a href="{{ route('lecturer.courses.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Course
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection