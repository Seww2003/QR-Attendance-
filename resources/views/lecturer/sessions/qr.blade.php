@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">QR Code for Session</h4>
                    <div>
                        @if($session->qr_token)
                            <span class="badge bg-success me-2">Active</span>
                        @else
                            <span class="badge bg-secondary me-2">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($session)
                    <div class="row mb-4">
                        <!-- Session Details -->
                        <div class="col-md-6">
                            <h5>Session Details</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Course</th>
                                    <td>{{ $session->course->course_code }} - {{ $session->course->course_name }}</td>
                                </tr>
                                <tr>
                                    <th>Topic</th>
                                    <td>{{ $session->topic ?? 'No topic specified' }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ \Carbon\Carbon::parse($session->date)->format('F j, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Time</th>
                                    <td>{{ date('h:i A', strtotime($session->start_time)) }} - {{ date('h:i A', strtotime($session->end_time)) }}</td>
                                </tr>
                                <tr>
                                    <th>QR Status</th>
                                    <td>
                                        @if($session->qr_token)
                                            <span class="text-success">Active</span>
                                            <br>
                                            <small>Generated: {{ \Carbon\Carbon::parse($session->qr_generated_at)->format('Y-m-d H:i:s') }}</small>
                                        @else
                                            <span class="text-danger">Not Generated</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($session->qr_token)
                                <tr>
                                    <th>QR Token</th>
                                    <td>
                                        <code style="font-size: 12px;">{{ $session->qr_token }}</code>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        
                        <!-- QR Code Display -->
                        <div class="col-md-6 text-center">
                            @if($session->qr_token)
                                <h5>QR Code</h5>
                                <div class="mb-3" style="background: white; padding: 20px; border-radius: 10px; display: inline-block;">
                                    {!! $qrCode !!}
                                </div>
                                <p class="text-muted">Scan this QR code to mark attendance</p>
                                <p class="text-muted small">
                                    <strong>Session ID:</strong> {{ $session->id }}<br>
                                    <strong>Token:</strong> {{ substr($session->qr_token, 0, 8) }}...
                                </p>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <h5>No Active QR Code</h5>
                                    <p>Generate a QR code to start attendance tracking</p>
                                    <form method="POST" action="{{ route('lecturer.sessions.qr.generate', $session->id) }}" class="mt-3">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-qr-code"></i> Generate QR Code
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($session->qr_token)
                    <!-- Instructions -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Instructions:</h6>
                                <ul class="mb-0">
                                    <li>Display this QR code during the session on projector or screen</li>
                                    <li>Students should scan it using their mobile app</li>
                                    <li>QR code is valid only for this session ({{ $session->date }})</li>
                                    <li>Do not share this QR code publicly before the session</li>
                                    <li>Deactivate QR after session ends to prevent unauthorized scans</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                     {{-- ACTION BUTTONS --}}
                    <div class="mt-4 d-flex flex-wrap gap-2">
                        <a href="{{ route('lecturer.courses.show', $session->course_id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Course
                        </a>

                        @if($session->qr_token)
                            <a href="{{ route('lecturer.sessions.attendance', $session->id) }}" class="btn btn-primary">
                                <i class="fas fa-list-check"></i> View Attendance
                            </a>

                            <button onclick="window.print()" class="btn btn-outline-primary">
                                <i class="fas fa-print"></i> Print QR Code
                            </button>

                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deactivateModal">
                                <i class="fas fa-times-circle"></i> Deactivate QR
                            </button>
                        @endif
                    </div>

                    @else
                    <!-- Session Not Found -->
                    <div class="alert alert-danger">
                        <h5>Error: Session Not Found</h5>
                        <p>The session you're trying to access does not exist or has been deleted.</p>
                        <a href="{{ route('lecturer.courses') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Courses
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate Confirmation Modal -->
@if($session && $session->qr_token)
<div class="modal fade" id="deactivateModal" tabindex="-1" aria-labelledby="deactivateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deactivateModalLabel">Confirm Deactivate QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning!</strong> Are you sure you want to deactivate this QR code?
                </div>
                <p>This will:</p>
                <ul>
                    <li>Invalidate the current QR code immediately</li>
                    <li>Stop accepting new attendance scans</li>
                    <li>Preserve existing attendance records</li>
                    <li>Require generating a new QR code for future scans</li>
                </ul>
                <p class="mb-0">
                    <strong>Session:</strong> {{ $session->course->course_code }}<br>
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($session->date)->format('F j, Y') }}<br>
                    <strong>QR Generated:</strong> {{ \Carbon\Carbon::parse($session->qr_generated_at)->format('h:i A') }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('lecturer.sessions.qr.deactivate', $session->id) }}">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle"></i> Confirm Deactivate
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Simple QR for Printing -->
<div class="d-none print-only">
    <div style="text-align: center; padding: 50px;">
        <h2>Attendance QR Code</h2>
        <p><strong>Course:</strong> {{ $session->course->course_code }} - {{ $session->course->course_name }}</p>
        <p><strong>Session:</strong> {{ $session->topic ?? 'Regular Session' }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($session->date)->format('F j, Y') }}</p>
        <p><strong>Time:</strong> {{ date('h:i A', strtotime($session->start_time)) }} - {{ date('h:i A', strtotime($session->end_time)) }}</p>
        <div style="margin: 30px 0;">
            {!! $qrCode ?? '' !!}
        </div>
        <p>Scan to mark attendance</p>
        <p style="font-size: 10px; margin-top: 50px;">Generated on: {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</p>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .btn, .alert, .modal, .card-header, .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-body {
            padding: 0 !important;
        }
        .print-only {
            display: block !important;
        }
        body {
            background: white !important;
        }
    }
    
    .card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    code {
        background: #f8f9fa;
        padding: 2px 6px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh QR code every 30 seconds (optional)
        setInterval(function() {
            if (document.querySelector('#qr-code-container')) {
                // You can add auto-refresh logic here if needed
            }
        }, 30000);
        
        // Print functionality
        window.printQR = function() {
            window.print();
        };
        
        // Copy QR token to clipboard
        window.copyToken = function() {
            const token = "{{ $session->qr_token ?? '' }}";
            if (token) {
                navigator.clipboard.writeText(token).then(function() {
                    alert('QR Token copied to clipboard!');
                });
            }
        };
    });
</script>
@endpush