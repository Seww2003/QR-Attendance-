<!-- {{-- resources/views/test-qr.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Test QR</title>
</head>
<body>
    <h2>Test QR Code</h2>
    
    @if($session)
        <p><strong>Session ID:</strong> {{ $session->id }}</p>
        <p><strong>Course:</strong> {{ $session->course->course_name }}</p>
        <p><strong>Token:</strong> {{ $session->qr_token }}</p>
        <p><strong>QR Data:</strong> <code>{{ $qrData }}</code></p>
        
        <div style="border: 2px solid #000; padding: 20px; display: inline-block;">
            {!! $qrCode !!}
        </div>
        
        <hr>
        
        <h3>Test Scan</h3>
        <button onclick="testScan()">Test Scan This QR</button>
        <div id="result"></div>
        
        <script>
        function testScan() {
            const qrData = @json($qrData);
            
            fetch('/qr-scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ qr_data: qrData })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('result').innerHTML = 
                    `<pre>${JSON.stringify(data, null, 2)}</pre>`;
            })
            .catch(error => {
                document.getElementById('result').innerHTML = 
                    `Error: ${error}`;
            });
        }
        </script>
    @else
        <p>No session found</p>
    @endif
</body>
</html> -->