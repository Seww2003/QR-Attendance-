<!DOCTYPE html>
<html>
<head>
    <title>Students Report - University Attendance System</title>
    <style>
        @page {
            margin: 15mm;
        }
        
        body { 
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif; 
            font-size: 9pt; 
            line-height: 1.3;
            color: #333;
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2c3e50;
        }
        
        .university-name {
            font-size: 14pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 16pt;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0;
        }
        
        .report-info {
            font-size: 8pt;
            color: #666;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .summary-box {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            border-left: 4px solid #3498db;
            font-size: 8pt;
        }
        
        .summary-item {
            margin-bottom: 3px;
        }
        
        .summary-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
            font-size: 8pt;
            page-break-inside: auto;
        }
        
        th { 
            background-color: #2c3e50 !important; 
            color: white !important; 
            padding: 8px 5px !important; 
            text-align: left !important;
            border: 1px solid #1a252f !important;
            font-weight: bold !important;
            font-size: 8pt !important;
        }
        
        td { 
            padding: 6px 5px !important; 
            border: 1px solid #ddd !important;
            vertical-align: middle !important;
        }
        
        tr:nth-child(even) td {
            background-color: #f9f9f9 !important;
        }
        
        .student-id {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .courses-count {
            text-align: center;
            font-weight: bold;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
        }
        
        .badge-courses {
            background-color: #e7f1ff;
            color: #0d6efd;
            border: 1px solid #cfe2ff;
        }
        
        .badge-nic {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .footer { 
            margin-top: 20px; 
            text-align: center; 
            color: #666; 
            font-size: 7pt; 
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        
        .copyright {
            font-size: 6pt;
            color: #999;
            margin-top: 5px;
        }
        
        .page-number {
            font-size: 8pt;
            color: #666;
            float: right;
        }
        
        .col-no { width: 30px; }
        .col-id { width: 80px; }
        .col-name { width: 120px; }
        .col-email { width: 130px; }
        .col-nic { width: 90px; }
        .col-phone { width: 90px; }
        .col-courses { width: 60px; }
        .col-date { width: 80px; }
        
        /* Fix for DomPDF rendering issues */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .table-container {
            overflow: visible !important;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="university-name">University Attendance System</div>
        <div class="report-title">Students Report</div>
        <div class="report-info">
            <strong>Generated on:</strong> {{ \Carbon\Carbon::now('Asia/Colombo')->format('Y-m-d h:i:s A') }}<br>
            <strong>Period:</strong> {{ $startDate }} to {{ $endDate }}
        </div>
    </div>
    
    <div class="summary-box">
        <div class="summary-item">
            <span class="summary-label">Report Type:</span>
            Students Master List
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Students:</span>
            <strong>{{ $data->count() }}</strong> records
        </div>
        <div class="summary-item">
            <span class="summary-label">Report ID:</span>
            STU-{{ date('YmdHis') }}
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="col-no text-center">#</th>
                    <th class="col-id">Student ID</th>
                    <th class="col-name">Name</th>
                    <th class="col-email">Email Address</th>
                    <th class="col-nic">NIC Number</th>
                    <th class="col-phone">Phone Number</th>
                    <th class="col-courses text-center">Courses</th>
                    <!-- <th class="col-date">Enrolled Date</th> -->
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $student)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="student-id">{{ $student->student_id }}</td>
                    <td>{{ $student->user->name }}</td>
                    <td>{{ $student->user->email }}</td>
                    <td>
                        @if($student->user->nic && $student->user->nic != '')
                            <span class="badge badge-nic">{{ $student->user->nic }}</span>
                        @else
                            <span style="color: #999;">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($student->phone && $student->phone != '')
                            {{ $student->phone }}
                        @else
                            <span style="color: #999;">N/A</span>
                        @endif
                    </td>
                    <td class="courses-count">
                        <span class="badge badge-courses">{{ $student->courses->count() }}</span>
                    </td>
                    <!-- <td>
                        {{ $student->created_at->format('Y-m-d') }}
                    </td> -->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="footer">
        <div class="page-number">Page 1 of 1</div>
        <div>
            <strong>University Attendance System</strong><br>
            This report is computer generated and does not require a signature
        </div>
        <div class="copyright">
            © {{ date('Y') }} University Attendance System. All rights reserved.
        </div>
    </div>
    
    <script type="text/javascript">
        // Force table headers to render with correct styling
        document.addEventListener('DOMContentLoaded', function() {
            var ths = document.querySelectorAll('th');
            ths.forEach(function(th) {
                th.style.backgroundColor = '#2c3e50';
                th.style.color = 'white';
                th.style.fontWeight = 'bold';
            });
        });
    </script>
</body>
</html>