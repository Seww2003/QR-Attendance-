{{-- resources/views/student/scanner.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-qrcode"></i> QR Code Scanner</h4>
                    <a href="{{ route('student.dashboard') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h5><i class="fas fa-info-circle"></i> Instructions:</h5>
                        <ol class="mb-0">
                            <li>Click "Start Scanner" button below</li>
                            <li>Allow camera access when prompted</li>
                            <li>Point camera at the QR code shown by your lecturer</li>
                            <li>Hold steady until QR code is detected</li>
                            <li>Click "Mark Attendance" to confirm</li>
                        </ol>
                    </div>

                    <div class="text-center mb-4">
                        <button id="start-scanner" class="btn btn-success btn-lg"><i class="fas fa-camera"></i> Start Scanner</button>
                        <button id="stop-scanner" class="btn btn-danger btn-lg" style="display:none;"><i class="fas fa-stop"></i> Stop Scanner</button>
                    </div>

                    <div id="qr-reader" style="width:100%; display:none;"></div>

                    <div id="camera-status" class="alert alert-warning text-center" style="display:none;">
                        <i class="fas fa-camera"></i> Requesting camera access...
                    </div>

                    <div id="session-info" class="card mt-4" style="display:none;">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-check-circle"></i> Session Detected</h5>
                        </div>
                        <div class="card-body">
                            <div id="session-details"></div>
                            <div class="text-center mt-3">
                                <button id="mark-attendance-btn" class="btn btn-success btn-lg">
                                    <i class="fas fa-calendar-check"></i> Mark Attendance
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="success-message" class="alert alert-success mt-3" style="display:none;"></div>
                    <div id="error-message" class="alert alert-danger mt-3" style="display:none;"></div>

                    <div id="loading-spinner" class="text-center mt-4" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Processing QR code...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startBtn = document.getElementById('start-scanner');
    const stopBtn = document.getElementById('stop-scanner');
    const qrDiv = document.getElementById('qr-reader');
    const cameraStatus = document.getElementById('camera-status');
    const sessionInfo = document.getElementById('session-info');
    const loadingSpinner = document.getElementById('loading-spinner');
    const errorMessage = document.getElementById('error-message');
    const successMessage = document.getElementById('success-message');
    let html5QrCode = null;
    let currentSessionId = null;
    let isScanning = false;

    function showError(message){
        errorMessage.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
        errorMessage.style.display = 'block';
        successMessage.style.display = 'none';
    }

    function showSuccess(message){
        successMessage.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
        successMessage.style.display = 'block';
        errorMessage.style.display = 'none';
    }

    function hideAllMessages() {
        errorMessage.style.display = 'none';
        successMessage.style.display = 'none';
        sessionInfo.style.display = 'none';
    }

    startBtn.addEventListener('click', () => {
        hideAllMessages();
        qrDiv.style.display = 'block';
        startBtn.style.display = 'none';
        stopBtn.style.display = 'inline-block';
        cameraStatus.style.display = 'block';
        cameraStatus.className = 'alert alert-warning text-center';
        cameraStatus.innerHTML = '<i class="fas fa-camera"></i> Requesting camera access...';

        html5QrCode = new Html5Qrcode("qr-reader");

        Html5Qrcode.getCameras().then(cameras => {
            if(cameras && cameras.length){
                html5QrCode.start(
                    { facingMode: "environment" },
                    { 
                        fps: 10, 
                        qrbox: { width: 250, height: 250 },
                        aspectRatio: 1.0
                    },
                    (decodedText, decodedResult) => {
                        if(isScanning) return; // Prevent multiple scans
                        isScanning = true;
                        
                        console.log("QR Code detected:", decodedText);
                        
                        // Stop scanner immediately
                        if(html5QrCode && html5QrCode.isScanning) {
                            html5QrCode.stop().then(() => {
                                cameraStatus.style.display = 'none';
                                qrDiv.style.display = 'none';
                                stopBtn.style.display = 'none';
                                startBtn.style.display = 'inline-block';
                                processQRData(decodedText);
                                isScanning = false;
                            }).catch(err => {
                                console.log("Error stopping scanner:", err);
                                cameraStatus.style.display = 'none';
                                qrDiv.style.display = 'none';
                                stopBtn.style.display = 'none';
                                startBtn.style.display = 'inline-block';
                                processQRData(decodedText);
                                isScanning = false;
                            });
                        } else {
                            cameraStatus.style.display = 'none';
                            qrDiv.style.display = 'none';
                            stopBtn.style.display = 'none';
                            startBtn.style.display = 'inline-block';
                            processQRData(decodedText);
                            isScanning = false;
                        }
                    },
                    (errorMessage) => {
                        // This is for verbose logging, not errors
                        console.log("QR Scan status:", errorMessage);
                    }
                ).then(() => {
                    cameraStatus.className = 'alert alert-success text-center';
                    cameraStatus.innerHTML = '<i class="fas fa-check-circle"></i> Camera active - scanning for QR codes...';
                }).catch(err => {
                    showError('Failed to start camera: ' + err);
                    resetScannerUI();
                });
            } else {
                showError('No camera found on your device.');
                resetScannerUI();
            }
        }).catch(err => {
            showError('Camera access denied. Please allow camera access.');
            resetScannerUI();
        });
    });

    stopBtn.addEventListener('click', () => {
        if(html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().then(() => {
                resetScannerUI();
            }).catch(err => {
                console.log("Error stopping scanner:", err);
                resetScannerUI();
            });
        } else {
            resetScannerUI();
        }
    });

    function resetScannerUI() {
        qrDiv.style.display = 'none';
        startBtn.style.display = 'inline-block';
        stopBtn.style.display = 'none';
        cameraStatus.style.display = 'none';
        isScanning = false;
    }

    function processQRData(qrData){
        console.log("Processing QR data:", qrData);
        loadingSpinner.style.display = 'block';
        hideAllMessages();
        
        fetch('{{ route("qr.process") }}', {
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body: JSON.stringify({ qr_data: qrData })
        }).then(response => {
            if(!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        }).then(data=>{
            console.log("QR process response:", data);
            loadingSpinner.style.display = 'none';
            
            if(data.success){
                currentSessionId = data.session_id;
                document.getElementById('session-details').innerHTML = `
                    <div class="session-detail-item">
                        <h5 class="text-primary">${data.course_name || 'Course'}</h5>
                    </div>
                    <div class="session-detail-item">
                        <strong><i class="fas fa-chalkboard-teacher"></i> Session:</strong> ${data.topic || 'General Session'}
                    </div>
                    <div class="session-detail-item">
                        <strong><i class="fas fa-calendar-day"></i> Date:</strong> ${data.date || 'N/A'}
                    </div>
                    <div class="session-detail-item">
                        <strong><i class="fas fa-clock"></i> Time:</strong> ${data.start_time || 'N/A'} - ${data.end_time || 'N/A'}
                    </div>
                    <div class="session-detail-item">
                        <strong><i class="fas fa-user-tie"></i> Lecturer:</strong> ${data.lecturer_name || 'N/A'}
                    </div>
                `;
                sessionInfo.style.display = 'block';
                
                // Auto scroll to session info
                sessionInfo.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                showError(data.message || 'Invalid QR code. Please try again.');
                // Show start button again to retry
                startBtn.style.display = 'inline-block';
            }
        }).catch(err=>{
            console.error("QR process error:", err);
            loadingSpinner.style.display = 'none';
            showError('Failed to process QR code. Please check your internet connection and try again.');
            // Show start button again to retry
            startBtn.style.display = 'inline-block';
        });
    }

    document.getElementById('mark-attendance-btn').addEventListener('click', ()=>{
        if(!currentSessionId) {
            showError('No valid session detected. Please scan the QR code again.');
            return;
        }
        
        loadingSpinner.style.display = 'block';
        sessionInfo.style.display = 'none';
        errorMessage.style.display = 'none';
        
        fetch('{{ route("attendance.mark") }}', {
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body: JSON.stringify({ session_id: currentSessionId })
        }).then(response => {
            if(!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        }).then(data=>{
            loadingSpinner.style.display = 'none';
            if(data.success){
                showSuccess(data.message || 'Attendance marked successfully!');
                // Disable the button to prevent multiple submissions
                document.getElementById('mark-attendance-btn').disabled = true;
                document.getElementById('mark-attendance-btn').innerHTML = '<i class="fas fa-check"></i> Attendance Marked';
                
                setTimeout(()=>{ 
                    window.location.href='{{ route("student.dashboard") }}'; 
                }, 2000);
            } else {
                showError(data.message || 'Failed to mark attendance.');
                // Re-enable the button
                document.getElementById('mark-attendance-btn').disabled = false;
            }
        }).catch(err=>{
            loadingSpinner.style.display = 'none';
            showError('Network error. Please try again.');
            console.error('Attendance mark error:', err);
            // Re-enable the button
            document.getElementById('mark-attendance-btn').disabled = false;
        });
    });

    // Initialize
    hideAllMessages();

});
</script>

<style>
#qr-reader { 
    margin: 0 auto; 
    max-width: 500px; 
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
#qr-reader video { 
    width: 100%; 
    border-radius: 10px; 
    border: 3px solid #007bff; 
}
#session-info { 
    animation: fadeIn 0.5s ease-in-out; 
    border: 2px solid #28a745;
}
.session-detail-item {
    margin-bottom: 10px;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 5px;
    border-left: 4px solid #007bff;
}
.btn-lg { 
    padding: 12px 24px; 
    font-size: 18px; 
    transition: all 0.3s;
}
.btn-lg:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
@keyframes fadeIn { 
    from {
        opacity: 0; 
        transform: translateY(-20px);
    } 
    to {
        opacity: 1; 
        transform: translateY(0);
    } 
}
.alert {
    animation: slideIn 0.3s ease-out;
}
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
@endpush
@endsection