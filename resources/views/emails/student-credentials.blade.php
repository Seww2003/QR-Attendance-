<!DOCTYPE html>
<html>
<head>
    <title>Your Student Account Credentials</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4e73df; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fc; }
        .credentials { background: white; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6c757d; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>QR Attendance System</h1>
            <h2>Your Student Account Credentials</h2>
        </div>
        
        <div class="content">
            <p>Hello {{ $user->name }},</p>
            
            <p>Your student account has been created in the QR Attendance System. Here are your login credentials:</p>
            
            <div class="credentials">
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Password:</strong> {{ $password }}</p>
                <p><strong>Student ID:</strong> {{ $user->student->student_id ?? 'N/A' }}</p>
            </div>
            
            <p><strong>Important:</strong> Please change your password after your first login for security reasons.</p>
            
            <p>
                <a href="{{ url('/login') }}" style="background: #4e73df; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    Login to System
                </a>
            </p>
            
            <p>If you have any questions, please contact the system administrator.</p>
            
            <p>Best regards,<br>
            QR Attendance System Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} QR Attendance System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>