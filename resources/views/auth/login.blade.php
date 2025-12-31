<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | QR Attendance</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6C63FF;
            --primary-dark: #554FD8;
            --light: #F8F9FF;
            --dark: #2D3748;
            --gradient: linear-gradient(135deg, #6C63FF 0%, #36D1DC 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            min-height: 100vh;
            background: linear-gradient(-45deg, #6C63FF, #36D1DC, #FF6584, #6C63FF);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated Background Circles */
        .bg-circles {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite linear;
        }
        
        .circle:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .circle:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 20%;
            right: 15%;
            animation-delay: 5s;
        }
        
        .circle:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 15%;
            animation-delay: 10s;
        }
        
        .circle:nth-child(4) {
            width: 100px;
            height: 100px;
            bottom: 10%;
            right: 10%;
            animation-delay: 15s;
        }
        
        /* Login Container */
        .login-container {
            width: 100%;
            max-width: 400px;
            z-index: 2;
            position: relative;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        /* Logo with Floating Animation */
        .logo-wrapper {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(108, 99, 255, 0.3);
            animation: floatIcon 6s ease-in-out infinite;
        }
        
        .logo-icon i {
            font-size: 36px;
            color: white;
        }
        
        .logo-text {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
        }
        
        /* Form Styles - Simple, No Animations */
        .form-group {
            margin-bottom: 25px;
        }
        
        .input-label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
            font-weight: 500;
            font-size: 14px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .form-input {
            width: 100%;
            padding: 15px 20px 15px 50px;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
        }
        
        /* Icons with Pulse Animation */
        .input-icon {
            position: absolute;
            left: 15px;
            top: 15px;
            color: #94A3B8;
            font-size: 20px;
            animation: pulseIcon 2s infinite;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 15px;
            background: none;
            border: none;
            color: #94A3B8;
            font-size: 18px;
            cursor: pointer;
            transition: color 0.3s ease;
            animation: pulseIcon 2s infinite;
            animation-delay: 1s;
        }
        
        .password-toggle:hover {
            color: var(--primary);
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0 30px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .remember-check {
            width: 18px;
            height: 18px;
            border: 2px solid #E2E8F0;
            border-radius: 4px;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .remember-check.checked {
            background: var(--gradient);
            border-color: var(--primary);
        }
        
        .remember-check.checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        .remember-text {
            color: var(--dark);
            font-size: 14px;
        }
        
        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        .btn-login {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(108, 99, 255, 0.3);
        }
        
        .btn-login i {
            margin-right: 10px;
        }
        
        .error-message {
            background: #FEE2E2;
            color: #DC2626;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 4px solid #DC2626;
        }
        
        .success-message {
            background: #DCFCE7;
            color: #16A34A;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 4px solid #16A34A;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #718096;
            font-size: 14px;
        }
        
        /* Animations - Only for Background and Icons */
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
            }
        }
        
        @keyframes floatIcon {
            0%, 100% { 
                transform: translateY(0) rotate(0deg); 
            }
            50% { 
                transform: translateY(-20px) rotate(5deg); 
            }
        }
        
        @keyframes pulseIcon {
            0%, 100% { 
                transform: scale(1); 
            }
            50% { 
                transform: scale(1.1); 
            }
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
            }
            
            .logo-icon {
                width: 70px;
                height: 70px;
            }
            
            .logo-icon i {
                font-size: 30px;
            }
            
            .logo-text {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background Circles -->
    <div class="bg-circles">
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
    </div>
    
    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <!-- Logo with Floating Animation -->
            <div class="logo-wrapper">
                <div class="logo-icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h1 class="logo-text">QR Attendance</h1>
            </div>
            
            <!-- Error Messages -->
            @if($errors->any())
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif
            
            @if(session('status'))
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    {{ session('status') }}
                </div>
            @endif
            
            <!-- Login Form - No Animations -->
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <!-- Email Field -->
                <div class="form-group">
                    <label class="input-label">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" 
                               name="email" 
                               id="email"
                               class="form-input" 
                               value="{{ old('email') }}" 
                               placeholder="Enter your email"
                               required 
                               autofocus>
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label class="input-label">Password</label>
                    <div class="input-wrapper">
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="form-input" 
                               placeholder="Enter your password"
                               required>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    <div class="remember-me" id="rememberMe">
                        <div class="remember-check" id="rememberCheck"></div>
                        <span class="remember-text">Remember me</span>
                        <input type="checkbox" name="remember" id="remember" style="display: none;">
                    </div>
                    <a href="#" class="forgot-link" id="forgotPassword">
                        Forgot Password?
                    </a>
                </div>
                
                <!-- Login Button -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>
            
            <!-- Footer -->
            <div class="footer">
                <p>© {{ date('Y') }} QR Attendance System</p>
            </div>
        </div>
    </div>
    
    <!-- JavaScript - Only Basic Functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password Toggle
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? 
                    '<i class="fas fa-eye"></i>' : 
                    '<i class="fas fa-eye-slash"></i>';
            });
            
            // Remember Me Checkbox
            const rememberMe = document.getElementById('rememberMe');
            const rememberCheck = document.getElementById('rememberCheck');
            const rememberInput = document.getElementById('remember');
            
            rememberMe.addEventListener('click', function() {
                rememberCheck.classList.toggle('checked');
                rememberInput.checked = rememberCheck.classList.contains('checked');
            });
            
            // Forgot Password
            document.getElementById('forgotPassword').addEventListener('click', function(e) {
                e.preventDefault();
                alert('Please contact your administrator to reset your password.');
            });
            
            // Form Validation
            const loginForm = document.getElementById('loginForm');
            
            loginForm.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();
                
                if (!email || !password) {
                    e.preventDefault();
                    alert('Please fill in all fields!');
                    return false;
                }
            });
            
            // Auto-focus email field
            setTimeout(() => {
                document.getElementById('email').focus();
            }, 300);
        });
    </script>
</body>
</html>