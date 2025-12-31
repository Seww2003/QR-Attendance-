{{-- resources/views/lecturer/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Lecturer Dashboard</h1>
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">My Courses</h5>
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
                            <h5 class="card-title">Sessions</h5>
                            <h2 class="mb-0">{{ $sessions }}</h2>
                        </div>
                        <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">My Courses</h5>
            <a href="{{ route('lecturer.courses.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Course
            </a>
        </div>
        <div class="card-body">
            @php
                $myCourses = Auth::user()->lecturer->courses()->take(5)->get();
            @endphp
            
            @if($myCourses->count() > 0)
                <div class="row">
                    @foreach($myCourses as $course)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $course->course_code }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $course->course_name }}</h6>
                                <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                                <a href="{{ route('lecturer.courses.show', $course) }}" class="btn btn-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-muted">No courses yet. Create your first course!</p>
            @endif
        </div>
    </div>
</div>
@endsection