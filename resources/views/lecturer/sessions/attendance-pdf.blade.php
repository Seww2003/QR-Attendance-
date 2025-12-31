<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
        body { font-family: Arial; font-size: 12px; }
        h1 { color: #333; text-align: center; }
        h3 { color: #666; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .present { color: green; }
        .absent { color: red; }
    </style>
</head>
<body>
    <h1>Attendance Report</h1>
    <h3>{{ $session->course->course_code }} - {{ $session->course->course_name }}</h3>
    <p><strong>Session:</strong> {{ $session->topic ?? 'Regular Session' }}</p>
    <p><strong>Date:</strong> {{ $session->date }}</p>
    <p><strong>Time:</strong> {{ $session->start_time }} - {{ $session->end_time }}</p>
    
    <hr>
    
    <h4>Attendance Summary</h4>
    <p>Total Students: {{ $session->course->students->count() }}</p>
    <p>Present: {{ $attendances->count() }}</p>
    <p>Absent: {{ $session->course->students->count() - $attendances->count() }}</p>
    
    <hr>
    
    <h4>Present Students</h4>
    @if($attendances->count() > 0)
        <table>
            <tr>
                <th>#</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Scanned Time</th>
            </tr>
            @foreach($attendances as $index => $attendance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $attendance->student->student_id ?? 'N/A' }}</td>
                <td>{{ $attendance->student->user->name ?? 'N/A' }}</td>
                <td>{{ $attendance->scanned_at ? \Carbon\Carbon::parse($attendance->scanned_at)->format('Y-m-d h:i A') : 'N/A' }}</td>
            </tr>
            @endforeach
        </table>
    @else
        <p>No attendance records found.</p>
    @endif
    
    <hr>
    
    <p style="text-align: center; font-size: 10px; margin-top: 30px;">
       <strong>Generated on:</strong> {{ \Carbon\Carbon::now('Asia/Colombo')->format('Y-m-d h:i:s A') }}<br>
    </p>
</body>
</html>