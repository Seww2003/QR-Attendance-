{{-- resources/views/lecturer/sessions/qr.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">QR Code for Session</h4>
                        <a href="{{ route('lecturer.courses.show', $session->course) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body text-center">
                    <!-- Session Info -->
                    <div class="mb-4">
                        <h5>{{ $session->course->course_name }}</h5>
                        <p class="text-muted">
                            {{ $session->date }} | {{ date('h:i A', strtotime($session->start_time)) }} - {{ date('h:i A', strtotime($session->end_time)) }}
                        </p>
                        <p><strong>Topic:</strong> {{ $session->topic ?: 'N/A' }}</p>
                    </div>

                    <!-- QR Code Display -->
                    <div class="mb-4 p-4 border rounded bg-light d-inline-block">
                        {!! $qrCode !!}
                    </div>

                    <!-- Session Status -->
                    <div class="mb-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            This QR code is valid only during the session time.
                            <br>
                            <small>Generated at: {{ $session->qr_generated_at->format('Y-m-d h:i A') }}</small>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-center gap-2">
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print"></i> Print QR Code
                        </button>
                        <a href="{{ route('lecturer.sessions.attendance', $session) }}" class="btn btn-success">
                            <i class="fas fa-users"></i> View Attendance
                        </a>
                    </div>

                    <!-- Instructions -->
                    <div class="mt-4 text-start">
                        <h6>Instructions:</h6>
                        <ol>
                            <li>Display this QR code to students during the session</li>
                            <li>Students should scan this using their camera</li>
                            <li>Attendance will be automatically recorded</li>
                            <li>QR code expires at {{ date('h:i A', strtotime($session->end_time)) }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- lecturer/sessions/qr.blade.php එකේ --}}
<div class="alert alert-info mt-3">
    <h6>Debug Info (For Testing):</h6>
    <p><strong>Session ID:</strong> {{ $session->id }}</p>
    <p><strong>QR Token:</strong> {{ $session->qr_token }}</p>
    <p><strong>QR Data:</strong> 
        <small><code id="qr-data">{{ json_encode([
            'session_id' => $session->id,
            'token' => $session->qr_token,
            'expires' => $session->date->format('Y-m-d') . ' ' . $session->end_time
        ]) }}</code></small>
    </p>
    <button onclick="copyQRData()" class="btn btn-sm btn-secondary">
        Copy QR Data
    </button>
</div>

<script>
function copyQRData() {
    const qrData = document.getElementById('qr-data').textContent;
    navigator.clipboard.writeText(qrData);
    alert('QR Data copied to clipboard!');
}
</script>
<style>
@media print {
    .card-header, .btn, .alert, .instructions {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .qr-code {
        text-align: center;
        margin-top: 50px;
    }
}
</style>
@endsection