{{-- resources/views/student/courses.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">My Courses</h1>

    @if($courses->count() > 0)
        <div class="row">
            @foreach($courses as $course)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->course_code }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $course->course_name }}</h6>
                        <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-chalkboard-teacher"></i> {{ $course->lecturer->user->name }}
                            </small>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-info">
                                {{ $course->sessions->count() }} Sessions
                            </span>
                            <a href="{{ route('student.courses.show', $course) }}" class="btn btn-sm btn-primary">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $courses->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5>No courses enrolled</h5>
                <p class="text-muted">You are not enrolled in any courses yet.</p>
            </div>
        </div>
    @endif
</div>
@endsection