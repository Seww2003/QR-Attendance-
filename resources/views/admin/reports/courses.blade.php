@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1>{{ $title }}</h1>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Lecturer</th>
                        <th>Description</th>
                        <th>Students</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $course)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $course->course_code }}</td>
                        <td>{{ $course->course_name }}</td>
                        <td>{{ $course->lecturer->user->name ?? 'N/A' }}</td>
                        <td>{{ Str::limit($course->description, 50) }}</td>
                        <td>{{ $course->students->count() }}</td>
                        <td>{{ $course->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection