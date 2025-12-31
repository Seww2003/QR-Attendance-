@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Student</h1>
        <a href="{{ route('admin.students') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Students
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.students.update', $student->id) }}" method="POST" id="studentForm">
                @csrf
                @method('PUT')
                
                <!-- Debug Info -->
                <input type="hidden" name="debug_student_id" value="{{ $student->id }}">
                <input type="hidden" name="debug_user_id" value="{{ $student->user_id }}">
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <h5>Validation Errors:</h5>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" 
                                   value="{{ old('name', $student->user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" 
                                   value="{{ old('email', $student->user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nic">NIC Number</label>
                            <input type="text" class="form-control @error('nic') is-invalid @enderror" 
                                   id="nic" name="nic" 
                                   value="{{ old('nic', $student->user->nic ?? '') }}">
                                   <!-- REMOVED: required attribute -->
                            <small class="form-text text-muted">Optional field</small>
                            @error('nic')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="student_id">Student ID *</label>
                            <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                                   id="student_id" name="student_id" 
                                   value="{{ old('student_id', $student->student_id) }}" required>
                            @error('student_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" 
                                   value="{{ old('phone', $student->phone) }}">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="batch">Batch</label>
                            <input type="text" class="form-control @error('batch') is-invalid @enderror" 
                                   id="batch" name="batch" 
                                   value="{{ old('batch', $student->batch) }}">
                            @error('batch')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="faculty">Faculty</label>
                            <input type="text" class="form-control @error('faculty') is-invalid @enderror" 
                                   id="faculty" name="faculty" 
                                   value="{{ old('faculty', $student->faculty) }}">
                            @error('faculty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Note:</strong> NIC field is optional. Leave empty if not applicable.
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Update Student
                    </button>
                    <button type="button" class="btn btn-info" onclick="checkForm()">
                        <i class="fas fa-eye"></i> Preview Data
                    </button>
                    <a href="{{ route('admin.students') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function checkForm() {
    // Get all form values
    const formData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        nic: document.getElementById('nic').value,
        student_id: document.getElementById('student_id').value,
        phone: document.getElementById('phone').value,
        batch: document.getElementById('batch').value,
        faculty: document.getElementById('faculty').value
    };
    
    console.log('Form Data to be submitted:', formData);
    
    // Show in alert
    let alertMessage = 'Form Data Preview:\n\n';
    for (const [key, value] of Object.entries(formData)) {
        alertMessage += `${key}: ${value || '(empty)'}\n`;
    }
    
    alert(alertMessage);
}

// Form submit handler
document.getElementById('studentForm').addEventListener('submit', function(e) {
    console.log('Form is submitting...');
    
    // Optional: Disable button to prevent double click
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
});
</script>
@endsection