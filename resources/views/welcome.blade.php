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
    
    <!-- Animate.css for additional animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        :root {
            --primary: #6C63FF;
            --primary-dark: #554FD8;
            --secondary: #36D1DC;
            --accent: #FF6584;
            --light: #F8F9FF;
            --dark: #1A1D2B;
            --gradient: linear-gradient(135deg, #6C63FF 0%, #36D1DC 100%);
            --gradient-hover: linear-gradient(135deg, #554FD8 0%, #2DC7D2 100%);
            --gradient-accent: linear-gradient(135deg, #FF6584 0%, #FF8E53 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', 'Poppins', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            min-height: 100vh;
            background: linear-gradient(-45deg, #1A1D2B, #2D1B69, #162447, #1A1D2B);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Enhanced Animated Background */
        .bg-animation {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
        }
        
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(108, 99, 255, 0.15), rgba(54, 209, 220, 0.1));
            animation: floatShape 25s infinite linear;
            filter: blur(40px);
        }
        
        .floating-shape:nth-child(1) {
            width: 300px;
            height: 300px;
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }
        
        .floating-shape:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 20%;
            right: 10%;
            animation-delay: 7s;
            background: linear-gradient(45deg, rgba(255, 101, 132, 0.15), rgba(255, 142, 83, 0.1));
        }
        
        .floating-shape:nth-child(3) {
            width: 250px;
            height: 250px;
            bottom: 15%;
            left: 10%;
            animation-delay: 14s;
            background: linear-gradient(45deg, rgba(54, 209, 220, 0.15), rgba(108, 99, 255, 0.1));
        }
        
        /* Floating QR Particles */
        .qr-particle {
            position: absolute;
            color: rgba(255, 255, 255, 0.1);
            font-size: 24px;
            animation: floatParticle 20s infinite linear;
        }
        
        /* Glass Morphism Container */
        .login-container {
            width: 100%;
            max-width: 420px;
            z-index: 2;
            position: relative;
        }
        
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent
            );
            transition: 0.5s;
        }
        
        .glass-card:hover::before {
            left: 100%;
        }
        
        /* Enhanced Logo */
        .logo-wrapper {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .logo-orb {
            width: 100px;
            height: 100px;
            background: var(--gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 
                0 10px 30px rgba(108, 99, 255, 0.4),
                inset 0 2px 10px rgba(255, 255, 255, 0.3);
            position: relative;
            animation: pulseOrb 4s ease-in-out infinite;
            transition: transform 0.5s ease;
        }
        
        .logo-orb:hover {
            transform: scale(1.1) rotate(10deg);
        }
        
        .logo-orb::after {
            content: '';
            position: absolute;
            width: 120%;
            height: 120%;
            border-radius: 50%;
            background: var(--gradient);
            z-index: -1;
            filter: blur(15px);
            opacity: 0.5;
            animation: pulseGlow 4s ease-in-out infinite;
        }
        
        .logo-orb i {
            font-size: 40px;
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        
        .logo-text {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(45deg, #fff, #e0e0ff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        
        .logo-tagline {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        /* Enhanced Form Elements */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .input-label {
            display: block;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .form-input {
            width: 100%;
            padding: 16px 20px 16px 50px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.08);
            color: white;
            backdrop-filter: blur(10px);
        }
        
        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .form-input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.2);
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-2px);
        }
        
        /* Enhanced Icons */
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.6);
            font-size: 18px;
            transition: all 0.3s ease;
            z-index: 2;
        }
        
        .form-input:focus + .input-icon {
            color: var(--primary);
            transform: translateY(-50%) scale(1.2);
        }
        
        /* Enhanced Password Toggle */
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: rgba(255, 255, 255, 0.6);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .password-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-50%) scale(1.1);
        }
        
        /* Enhanced Form Options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 25px 0 30px;
        }
        
        .remember-container {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .custom-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            position: relative;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .custom-checkbox.checked {
            border-color: var(--primary);
            background: var(--gradient);
        }
        
        .custom-checkbox.checked::after {
            content: '✓';
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        .remember-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            transition: color 0.3s ease;
        }
        
        .remember-container:hover .remember-text {
            color: white;
        }
        
        /* Enhanced Forgot Password */
        .forgot-link {
            color: var(--secondary);
            text-decoration: none;
            font-size: 14px;
            position: relative;
            padding-bottom: 2px;
            transition: all 0.3s ease;
        }
        
        .forgot-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--secondary);
            transition: width 0.3s ease;
        }
        
        .forgot-link:hover {
            color: white;
        }
        
        .forgot-link:hover::after {
            width: 100%;
        }
        
        /* Enhanced Login Button */
        .btn-login {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }
        
        .btn-login:hover {
            background: var(--gradient-hover);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(108, 99, 255, 0.4);
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:active {
            transform: translateY(-1px);
        }
        
        /* Enhanced Messages */
        .error-message {
            background: rgba(220, 38, 38, 0.1);
            color: #FCA5A5;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 4px solid #EF4444;
            backdrop-filter: blur(10px);
            animation: slideInDown 0.5s ease;
        }
        
        .success-message {
            background: rgba(34, 197, 94, 0.1);
            color: #86EFAC;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 4px solid #22C55E;
            backdrop-filter: blur(10px);
            animation: slideInDown 0.5s ease;
        }
        
        /* Enhanced Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            position: relative;
            padding-top: 20px;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50%;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }
        
        /* Animations */
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        @keyframes floatShape {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
            }
        }
        
        @keyframes floatParticle {
            0% {
                transform: translate(0, 0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translate(var(--tx, 100px), var(--ty, -1000px)) rotate(360deg);
                opacity: 0;
            }
        }
        
        @keyframes pulseOrb {
            0%, 100% { 
                transform: scale(1); 
            }
            50% { 
                transform: scale(1.05); 
            }
        }
        
        @keyframes pulseGlow {
            0%, 100% { 
                opacity: 0.5; 
                transform: scale(1);
            }
            50% { 
                opacity: 0.8; 
                transform: scale(1.1);
            }
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* QR Code Loading Animation */
        .qr-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--dark);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }
        
        .qr-loader-content {
            text-align: center;
        }
        
        .qr-loader-animation {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            position: relative;
        }
        
        .qr-loader-animation::before,
        .qr-loader-animation::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border: 3px solid transparent;
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .qr-loader-animation::after {
            border-top-color: var(--secondary);
            animation-delay: 0.5s;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive Design */
        @media (max-width: 576px) {
            .glass-card {
                padding: 30px 20px;
            }
            
            .logo-orb {
                width: 80px;
                height: 80px;
            }
            
            .logo-orb i {
                font-size: 32px;
            }
            
            .logo-text {
                font-size: 28px;
            }
            
            .form-options {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
        
        /* Additional Decorative Elements */
        .decorative-line {
            position: absolute;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            width: 100px;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <!-- Loading Animation -->
    <div class="qr-loader" id="loader">
        <div class="qr-loader-content">
            <div class="qr-loader-animation"></div>
            <h4 style="color: white; margin-top: 20px;">Initializing QR System...</h4>
        </div>
    </div>
    
    <!-- Enhanced Background Animation -->
    <div class="bg-animation" id="bgAnimation"></div>
    
    <!-- Glass Morphism Login Container -->
    <div class="login-container animate__animated animate__fadeIn">
        <div class="decorative-line"></div>
        
        <div class="glass-card">
            <!-- Enhanced Logo -->
            <div class="logo-wrapper">
                <div class="logo-orb">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h1 class="logo-text">QR ATTENDANCE</h1>
                
            </div>
            
            <!-- Enhanced Messages -->
            @if($errors->any())
                <div class="error-message animate__animated animate__shakeX">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif
            
            @if(session('status'))
                <div class="success-message animate__animated animate__bounceIn">
                    <i class="fas fa-check-circle"></i>
                    {{ session('status') }}
                </div>
            @endif
            
            <!-- Enhanced Login Form -->
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
                            <i class="fas fa-user-circle"></i>
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
                    <div class="remember-container" id="rememberContainer">
                        <div class="custom-checkbox" id="rememberCheck"></div>
                        <span class="remember-text">Remember me</span>
                        <input type="checkbox" name="remember" id="remember" style="display: none;">
                    </div>
                    <a href="#" class="forgot-link" id="forgotPassword">
                        Forgot Password?
                    </a>
                </div>
                
                <!-- Enhanced Login Button -->
                <button type="submit" class="btn-login" id="loginButton">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Access System</span>
                </button>
            </form>
            
            <!-- Enhanced Footer -->
            <!-- <div class="footer">
                <p>© {{ date('Y') }} QR Attendance System v2.0</p>
                <p style="margin-top: 5px; font-size: 12px; opacity: 0.6;">
                    <i class="fas fa-shield-alt"></i> Secure • <i class="fas fa-bolt"></i> Fast • <i class="fas fa-check-circle"></i> Reliable
                </p>
            </div> -->
        </div>
    </div>
    
    <!-- Enhanced JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loader after page loads
            setTimeout(() => {
                document.getElementById('loader').style.opacity = '0';
                setTimeout(() => {
                    document.getElementById('loader').style.display = 'none';
                }, 500);
            }, 1000);
            
            // Generate floating QR particles
            generateQRParticles();
            
            // Password Toggle with enhanced animation
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Enhanced icon animation
                this.style.transform = 'translateY(-50%) scale(1.2)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-50%) scale(1)';
                }, 200);
                
                this.innerHTML = type === 'password' ? 
                    '<i class="fas fa-eye"></i>' : 
                    '<i class="fas fa-eye-slash"></i>';
            });
            
            // Enhanced Remember Me
            const rememberContainer = document.getElementById('rememberContainer');
            const rememberCheck = document.getElementById('rememberCheck');
            const rememberInput = document.getElementById('remember');
            
            rememberContainer.addEventListener('click', function() {
                rememberCheck.classList.toggle('checked');
                rememberInput.checked = rememberCheck.classList.contains('checked');
                
                // Add bounce animation
                rememberCheck.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    rememberCheck.style.transform = 'scale(1)';
                }, 200);
            });
            
            // Enhanced Forgot Password
            document.getElementById('forgotPassword').addEventListener('click', function(e) {
                e.preventDefault();
                
                // Create a beautiful modal-like effect
                const modal = document.createElement('div');
                modal.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.8);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 1000;
                    backdrop-filter: blur(10px);
                `;
                
                modal.innerHTML = `
                    <div style="
                        background: linear-gradient(135deg, rgba(26, 29, 43, 0.95), rgba(45, 27, 105, 0.95));
                        padding: 30px;
                        border-radius: 20px;
                        max-width: 400px;
                        width: 90%;
                        border: 1px solid rgba(108, 99, 255, 0.3);
                        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
                        text-align: center;
                    ">
                        <div style="
                            width: 60px;
                            height: 60px;
                            background: linear-gradient(135deg, #6C63FF, #36D1DC);
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto 20px;
                        ">
                            <i class="fas fa-key" style="color: white; font-size: 24px;"></i>
                        </div>
                        <h3 style="color: white; margin-bottom: 15px;">Reset Password</h3>
                        <p style="color: rgba(255, 255, 255, 0.7); margin-bottom: 25px;">
                            Please contact your system administrator to reset your password.
                        </p>
                        <button id="closeModal" style="
                            background: linear-gradient(135deg, #6C63FF, #36D1DC);
                            color: white;
                            border: none;
                            padding: 12px 30px;
                            border-radius: 8px;
                            cursor: pointer;
                            font-weight: 600;
                            transition: all 0.3s ease;
                        ">
                            Close
                        </button>
                    </div>
                `;
                
                document.body.appendChild(modal);
                
                document.getElementById('closeModal').addEventListener('click', function() {
                    modal.style.opacity = '0';
                    setTimeout(() => {
                        document.body.removeChild(modal);
                    }, 300);
                });
            });
            
            // Enhanced Form Validation
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            
            loginForm.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();
                
                if (!email || !password) {
                    e.preventDefault();
                    
                    // Create error message
                    let errorDiv = document.querySelector('.error-message');
                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'error-message animate__animated animate__shakeX';
                        errorDiv.innerHTML = `
                            <i class="fas fa-exclamation-circle"></i>
                            Please fill in all fields!
                        `;
                        loginForm.insertBefore(errorDiv, loginForm.firstChild);
                        
                        // Remove after 5 seconds
                        setTimeout(() => {
                            errorDiv.style.opacity = '0';
                            setTimeout(() => {
                                errorDiv.remove();
                            }, 300);
                        }, 5000);
                    }
                    
                    // Shake animation for empty fields
                    if (!email) {
                        document.getElementById('email').style.borderColor = '#EF4444';
                        setTimeout(() => {
                            document.getElementById('email').style.borderColor = '';
                        }, 1000);
                    }
                    if (!password) {
                        document.getElementById('password').style.borderColor = '#EF4444';
                        setTimeout(() => {
                            document.getElementById('password').style.borderColor = '';
                        }, 1000);
                    }
                    
                    return false;
                }
                
                // Add loading state to button
                loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
                loginButton.disabled = true;
            });
            
            // Input focus effects
            document.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
            });
            
            // Auto-focus email field
            setTimeout(() => {
                document.getElementById('email').focus();
            }, 500);
        });
        
        // Function to generate floating QR particles
        function generateQRParticles() {
            const bgAnimation = document.getElementById('bgAnimation');
            const particles = 20;
            
            for (let i = 0; i < particles; i++) {
                const particle = document.createElement('div');
                particle.className = 'qr-particle';
                particle.innerHTML = '■';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.fontSize = (Math.random() * 20 + 10) + 'px';
                particle.style.opacity = Math.random() * 0.3 + 0.1;
                particle.style.setProperty('--tx', (Math.random() * 200 - 100) + 'px');
                particle.style.setProperty('--ty', (-Math.random() * 1000 - 100) + 'px');
                particle.style.animationDelay = Math.random() * 20 + 's';
                bgAnimation.appendChild(particle);
            }
        }
    </script>
</body>
</html>