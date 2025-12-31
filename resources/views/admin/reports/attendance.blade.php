@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1>{{ $title }}</h1>
            @if($startDate && $endDate)
                <p>Period: {{ $startDate }} to {{ $endDate }}</p>
            @endif
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Session</th>
                        <th>Attended At</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $attendance)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $attendance->student->user->name }}</td>
                        <td>{{ $attendance->session->course->course_name ?? 'N/A' }}</td>
                        <td>{{ $attendance->session->title ?? 'N/A' }}</td>
                        <td>{{ $attendance->attended_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $attendance->status }}</td>
                        <td>{{ $attendance->remarks }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection