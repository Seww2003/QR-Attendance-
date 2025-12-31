@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Courses Management</h1>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Course
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Lecturer</th>
                            <th>Students</th>
                            <th>Credit Hours</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                        <tr>
                            <td>{{ $course->course_code }}</td>
                            <td>{{ $course->name }}</td>
                            <td>
                                @if($course->lecturer)
                                    {{ $course->lecturer->user->name }}
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $course->students_count ?? $course->students->count() }}
                                </span>
                            </td>
                            <td>{{ $course->credit_hours ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
<!--                                 
                                 <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this course?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr> -->
                        
                        @empty
                        <tr> 
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-book fa-2x mb-3"></i><br>
                                No courses found. Add your first course!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                @if($courses->count() > 0)
                    {{ $courses->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Add any JavaScript functionality if needed
    });
</script>
@endpush