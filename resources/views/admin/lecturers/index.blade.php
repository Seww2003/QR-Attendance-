@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lecturers Management</h1>
        <a href="{{ route('admin.lecturers.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Add New Lecturer
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lecturers as $lecturer)
                        <tr>
                            <td>{{ $lecturer->employee_id }}</td>
                            <td>{{ $lecturer->user->name }}</td>
                            <td>{{ $lecturer->user->email }}</td>
                            <td>{{ $lecturer->department ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.lecturers.show', $lecturer->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $lecturers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection