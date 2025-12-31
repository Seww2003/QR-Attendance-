{{-- resources/views/partials/lecturer-sidebar.blade.php --}}
<div class="nav flex-column">
    <a href="{{ route('lecturer.dashboard') }}" class="nav-link {{ request()->routeIs('lecturer.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="{{ route('lecturer.courses') }}" class="nav-link {{ request()->routeIs('lecturer.courses*') ? 'active' : '' }}">
        <i class="fas fa-book"></i> Courses
    </a>
    
</div>