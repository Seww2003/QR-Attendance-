@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create Session for {{ $course->course_code }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('lecturer.sessions.store', $course->id) }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="topic" class="form-label">Session Topic (Optional)</label>
                            <input type="text" class="form-control" id="topic" name="topic" 
                                   placeholder="e.g., Introduction to Laravel">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date *</label>
                                <input type="date" class="form-control" id="date" name="date" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="start_time" class="form-label">Start Time *</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" 
                                       value="09:00" required>
                            </div>
                            <div class="col-md-3">
                                <label for="end_time" class="form-label">End Time *</label>
                                <input type="time" class="form-control" id="end_time" name="end_time" 
                                       value="11:00" required>
                            </div>

                            <div class="mb-3">
    <label for="qr_duration" class="form-label">QR Code Active Time *</label>
    <select name="qr_duration" id="qr_duration" class="form-control" required>
        <option value="">-- Select Time --</option>
        <option value="5">5 Minutes</option>
        <option value="10">10 Minutes</option>
        <option value="15">15 Minutes</option>
        <option value="30">30 Minutes</option>
    </select>
</div>







                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create Session
                            </button>
                            <a href="{{ route('lecturer.courses.show', $course->id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection