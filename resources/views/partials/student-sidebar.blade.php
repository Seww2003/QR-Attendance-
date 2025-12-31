{{-- resources/views/partials/student-sidebar.blade.php --}}
<div class="nav flex-column">
    <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="{{ route('student.courses') }}" class="nav-link {{ request()->routeIs('student.courses*') ? 'active' : '' }}">
        <i class="fas fa-book"></i> My Courses
    </a>
    <a href="{{ route('student.attendance') }}" class="nav-link {{ request()->routeIs('student.attendance') ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i> Attendance
    </a>
    <a href="{{ route('qr.scanner') }}" class="nav-link {{ request()->routeIs('qr.*') ? 'active' : '' }}">
        <i class="fas fa-qrcode"></i> QR Scanner
    </a>
</div>