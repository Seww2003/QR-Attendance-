@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
    <div class="col">
        <h1>{{ $title }}</h1>
        @if($startDate && $endDate)
            <p class="text-muted">Period: {{ $startDate }} to {{ $endDate }}</p>
        @endif
    </div>
    <div class="col-auto">
        <!-- PDF Download Button -->
        <form action="{{ route('admin.reports.generate') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="report_type" value="{{ $reportType }}">
            <input type="hidden" name="start_date" value="{{ $startDate }}">
            <input type="hidden" name="end_date" value="{{ $endDate }}">
            <input type="hidden" name="format" value="pdf">
            
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Download PDF
            </button>
        </form>
        
        <a href="{{ route('admin.reports') }}" class="btn btn-secondary ml-2">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>
        <div class="col-auto">
            <!-- Download Options -->
            <div class="btn-group">
                <!-- <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-download"></i> Download
                </button> -->
                <div class="dropdown-menu">
                    <form action="{{ route('admin.reports.generate') }}" method="POST" class="p-2">
                        @csrf
                        <input type="hidden" name="report_type" value="{{ $reportType }}">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        
                        <button type="submit" name="format" value="csv" class="dropdown-item">
                            <i class="fas fa-file-csv text-success"></i> Download CSV
                        </button>
                        
                        <button type="submit" name="format" value="excel" class="dropdown-item">
                            <i class="fas fa-file-excel text-success"></i> Download Excel
                        </button>
                        
                        <button type="submit" name="format" value="pdf" class="dropdown-item">
                            <i class="fas fa-file-pdf text-danger"></i> Download PDF
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- <a href="{{ route('admin.reports') }}" class="btn btn-secondary ml-2">
                <i class="fas fa-arrow-left"></i> Back
            </a> -->
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Report Data</h5>
                <span class="badge badge-info">{{ $data->count() }} records</span>
            </div>
        </div>
        <div class="card-body">
            @if($data->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No data found for this report.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                @switch($reportType)
                                    @case('students')
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>NIC</th>
                                        <th>Phone</th>
                                        <th>Courses</th>
                                        <th>Created At</th>
                                        @break
                                    
                                    @case('lecturers')
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Qualification</th>
                                        <th>Courses</th>
                                        @break
                                    
                                    @case('courses')
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                        <th>Lecturer</th>
                                        <th>Description</th>
                                        <th>Students</th>
                                        <th>Created At</th>
                                        @break
                                    
                                    @case('attendance')
                                        <th>Student</th>
                                        <th>Course</th>
                                        <th>Session</th>
                                        <th>Attended At</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        @break
                                @endswitch
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                
                                @switch($reportType)
                                    @case('students')
                                        <td>{{ $item->student_id }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->user->email }}</td>
                                        <td>{{ $item->user->nic }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td><span class="badge badge-primary">{{ $item->courses->count() }}</span></td>
                                        <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                        @break
                                    
                                    @case('lecturers')
                                        <td>{{ $item->employee_id }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->user->email }}</td>
                                        <td>{{ $item->department }}</td>
                                        <td>{{ $item->qualification }}</td>
                                        <td><span class="badge badge-primary">{{ $item->courses->count() }}</span></td>
                                        @break
                                    
                                    @case('courses')
                                        <td>{{ $item->course_code }}</td>
                                        <td>{{ $item->course_name }}</td>
                                        <td>{{ $item->lecturer->user->name ?? 'N/A' }}</td>
                                        <td>{{ Str::limit($item->description, 50) }}</td>
                                        <td><span class="badge badge-primary">{{ $item->students->count() }}</span></td>
                                        <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                        @break
                                    
                                    @case('attendance')
                                        <td>{{ $item->student->user->name ?? 'N/A' }}</td>
                                        <td>{{ $item->session->course->course_name ?? 'N/A' }}</td>
                                        <td>{{ $item->session->title ?? 'N/A' }}</td>
                                        <td>{{ $item->attended_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @if($item->status === 'present')
                                                <span class="badge badge-success">Present</span>
                                            @elseif($item->status === 'absent')
                                                <span class="badge badge-danger">Absent</span>
                                            @else
                                                <span class="badge badge-warning">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->remarks }}</td>
                                        @break
                                @endswitch
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection