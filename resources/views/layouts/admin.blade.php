<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Sistem Pendaftaran Pasien')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #f0f2f5; }
        .sidebar {
            min-height: 100vh;
            background: #1e3a5f;
            width: 250px;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar .brand {
            padding: 1.5rem 1rem;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0.1rem 0.5rem;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.15);
        }
        .sidebar .nav-link i { width: 20px; }
        .main-wrapper { margin-left: 250px; min-height: 100vh; }
        .top-navbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .main-content { padding: 1.5rem; }
        .card { border: none; box-shadow: 0 0.125rem 0.5rem rgba(0,0,0,.08); border-radius: 0.75rem; }
        .card-header { background: transparent; border-bottom: 1px solid rgba(0,0,0,.08); font-weight: 600; }
        .stat-card { border-left: 4px solid; }
        .stat-card.primary { border-color: #0d6efd; }
        .stat-card.success { border-color: #198754; }
        .stat-card.warning { border-color: #ffc107; }
        .stat-card.danger { border-color: #dc3545; }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column">
    <div class="brand">
        <i class="bi bi-hospital me-2"></i>Pendaftaran Pasien
        <div class="small text-white-50 fw-normal mt-1">Panel Admin</div>
    </div>
    <ul class="nav flex-column py-2 flex-grow-1">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dokter.*') ? 'active' : '' }}" href="{{ route('admin.dokter.index') }}">
                <i class="bi bi-person-badge me-2"></i>Dokter
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}" href="{{ route('admin.jadwal.index') }}">
                <i class="bi bi-calendar3 me-2"></i>Jadwal Praktek
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.pendaftaran.*') ? 'active' : '' }}" href="{{ route('admin.pendaftaran.index') }}">
                <i class="bi bi-clipboard2-pulse me-2"></i>Pendaftaran
            </a>
        </li>
    </ul>
    <div class="p-3 border-top border-white border-opacity-10">
        <div class="text-white-50 small mb-2">{{ auth()->user()->name }}</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                <i class="bi bi-box-arrow-right me-1"></i>Logout
            </button>
        </form>
    </div>
</div>

<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Top Navbar -->
    <div class="top-navbar d-flex align-items-center justify-content-between">
        <h6 class="mb-0 text-muted">@yield('page-title', 'Dashboard')</h6>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-danger">Admin</span>
            <span class="small text-muted">{{ auth()->user()->email }}</span>
        </div>
    </div>

    <!-- Flash Messages -->
    <div class="main-content pb-0">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>

    <!-- Content -->
    <div class="main-content">
        @yield('content')
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Auto dismiss alerts -->
<script>
    setTimeout(function () {
        document.querySelectorAll('.alert').forEach(function (alert) {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        });
    }, 5000);
</script>

@stack('scripts')
</body>
</html>
