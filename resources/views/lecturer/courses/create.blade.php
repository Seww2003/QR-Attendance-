{{-- resources/views/lecturer/courses/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create New Course</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('lecturer.courses.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="course_code" class="form-label">Course Code *</label>
                            <input type="text" class="form-control @error('course_code') is-invalid @enderror" 
                                   id="course_code" name="course_code" value="{{ old('course_code') }}" required>
                            @error('course_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="course_name" class="form-label">Course Name *</label>
                            <input type="text" class="form-control @error('course_name') is-invalid @enderror" 
                                   id="course_name" name="course_name" value="{{ old('course_name') }}" required>
                            @error('course_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('lecturer.courses') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection