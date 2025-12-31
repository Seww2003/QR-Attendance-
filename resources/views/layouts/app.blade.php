{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            color: white;
        }
        .sidebar a {
            color: #bdc3c7;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            transition: all 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left: 4px solid #3498db;
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
        }
        .btn-primary {
            background: linear-gradient(45deg, #3498db, #2980b9);
            border: none;
        }
        .btn-success {
            background: linear-gradient(45deg, #2ecc71, #27ae60);
            border: none;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</head>
<body class="font-sans antialiased">
    <div class="min-vh-100 d-flex">
        <!-- Sidebar -->
        <nav class="sidebar d-none d-md-block" style="width: 250px;">
            <div class="p-3">
                <h4 class="text-center mb-4">
                    <i class="fas fa-qrcode"></i> QR Attendance
                </h4>
                <div class="text-center mb-4">
                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                    <small class="badge bg-info">{{ ucfirst(Auth::user()->role) }}</small>
                </div>
                
                <hr>
                
                @if(Auth::user()->isAdmin())
                    @include('partials.admin-sidebar')
                @elseif(Auth::user()->isLecturer())
                    @include('partials.lecturer-sidebar')
                @elseif(Auth::user()->isStudent())
                    @include('partials.student-sidebar')
                @endif
                
                <hr>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Top Navigation (Mobile) -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm d-md-none">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <i class="fas fa-qrcode"></i> QR Attendance
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        @if(Auth::user()->isAdmin())
                            @include('partials.admin-sidebar')
                        @elseif(Auth::user()->isLecturer())
                            @include('partials.lecturer-sidebar')
                        @elseif(Auth::user()->isStudent())
                            @include('partials.student-sidebar')
                        @endif
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>