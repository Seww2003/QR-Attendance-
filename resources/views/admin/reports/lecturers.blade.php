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
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Qualification</th>
                        <th>Courses</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $lecturer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $lecturer->employee_id }}</td>
                        <td>{{ $lecturer->user->name }}</td>
                        <td>{{ $lecturer->user->email }}</td>
                        <td>{{ $lecturer->department }}</td>
                        <td>{{ $lecturer->qualification }}</td>
                        <td>{{ $lecturer->courses->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection