<div class="nav flex-column">
    <!-- Dashboard -->
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>

    <!-- Students -->
    <a href="{{ route('admin.students') }}" class="nav-link {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
        <i class="fas fa-users"></i> Students
    </a>

    <!-- Lecturers -->
    <a href="{{ route('admin.lecturers') }}" class="nav-link {{ request()->routeIs('admin.lecturers*') ? 'active' : '' }}">
        <i class="fas fa-chalkboard-teacher"></i> Lecturers
    </a>

    <!-- Courses -->
    <a href="{{ route('admin.courses') }}" class="nav-link {{ request()->routeIs('admin.courses*') ? 'active' : '' }}">
        <i class="fas fa-book"></i> Courses
    </a>

    <!-- Reports -->
    <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i> Reports
    </a>

    <!-- Settings -->
    <!-- <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
        <i class="fas fa-cog"></i> Settings
    </a> -->
</div>