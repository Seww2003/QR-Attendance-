@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Course</h1>
        <a href="{{ route('admin.courses') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Courses
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.courses.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="course_code">Course Code *</label>
                            <input type="text" class="form-control @error('course_code') is-invalid @enderror" 
                                   id="course_code" name="course_code" 
                                   value="{{ old('course_code') }}" required>
                            @error('course_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">e.g., CS101, MATH201</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Course Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">e.g., Introduction to Programming</small>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lecturer_id">Lecturer (Optional)</label>
                            <select class="form-control @error('lecturer_id') is-invalid @enderror" 
                                    id="lecturer_id" name="lecturer_id">
                                <option value="">Select Lecturer</option>
                                @foreach($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}" 
                                        {{ old('lecturer_id') == $lecturer->id ? 'selected' : '' }}>
                                        {{ $lecturer->user->name }} ({{ $lecturer->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('lecturer_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="credit_hours">Credit Hours (Optional)</label>
                            <input type="number" class="form-control @error('credit_hours') is-invalid @enderror" 
                                   id="credit_hours" name="credit_hours" 
                                   value="{{ old('credit_hours') }}" min="1">
                            @error('credit_hours')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Course
                    </button>
                    <a href="{{ route('admin.courses') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection