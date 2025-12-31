@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lecturer Details</h1>
        <div>
            <a href="{{ route('admin.lecturers') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Lecturers
            </a>
            <a href="{{ route('admin.lecturers.edit', $lecturer->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column - Lecturer Information -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lecturer Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Employee ID:</th>
                            <td>{{ $lecturer->employee_id }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $lecturer->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $lecturer->user->email }}</td>
                        </tr>
                        <tr>
                            <th>NIC:</th>
                            <td>{{ $lecturer->user->nic ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Department:</th>
                            <td>{{ $lecturer->department ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Qualification:</th>
                            <td>{{ $lecturer->qualification ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $lecturer->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At:</th>
                            <td>{{ $lecturer->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                    
                    <div class="mt-4">
                        <form action="{{ route('admin.lecturers.resend-credentials', $lecturer->user_id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-envelope"></i> Resend Credentials
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.lecturers.destroy', $lecturer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this lecturer?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Lecturer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Assigned Courses -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Assigned Courses</h6>
                    <span class="badge badge-primary">
                        {{ $lecturer->courses->count() }} Courses
                    </span>
                </div>
                <div class="card-body">
                    @if($lecturer->courses->count() > 0)
                        <div class="list-group">
                            @foreach($lecturer->courses as $course)
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <strong>{{ $course->course_code }}</strong> - {{ $course->name }}
                                    </h6>
                                    <small class="text-muted">
                                        {{ $course->students->count() }} students
                                    </small>
                                </div>
                                <p class="mb-1">{{ Str::limit($course->description, 100) }}</p>
                                <small class="text-muted">
                                    Credit Hours: {{ $course->credit_hours ?? 'N/A' }}
                                </small>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No courses assigned to this lecturer.
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus"></i> Assign Course
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.lecturers.edit', $lecturer->id) }}" class="btn btn-primary btn-block">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <form action="{{ route('admin.lecturers.resend-credentials', $lecturer->user_id) }}" method="POST" class="d-inline-block w-100">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-block">
                                    <i class="fas fa-envelope"></i> Resend Login
                                </button>
                            </form>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-info btn-block">
                                <i class="fas fa-chart-bar"></i> View Reports
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <form action="{{ route('admin.lecturers.destroy', $lecturer->id) }}" method="POST" class="d-inline-block w-100" onsubmit="return confirm('Delete this lecturer?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Add any JavaScript if needed
    });
</script>
@endsection