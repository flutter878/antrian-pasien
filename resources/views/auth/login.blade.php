<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Pendaftaran Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); min-height: 100vh; }
        .card { border: none; border-radius: 1rem; box-shadow: 0 1rem 3rem rgba(0,0,0,.2); }
        .card-header { background: #0d6efd; color: #fff; border-radius: 1rem 1rem 0 0 !important; padding: 1.5rem; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card">
                <div class="card-header text-center">
                    <i class="bi bi-hospital fs-2 d-block mb-2"></i>
                    <h4 class="mb-0 fw-bold">Masuk ke Sistem</h4>
                    <p class="mb-0 small opacity-75">Sistem Informasi Pendaftaran Pasien Online</p>
                </div>
                <div class="card-body p-4">

                    @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        @foreach($errors->all() as $error)
                        {{ $error }}<br>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="contoh@email.com" required autofocus>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Masukkan password" required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label text-muted small" for="remember">Ingat saya</label>
                            </div>
                            @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="small text-primary">Lupa password?</a>
                            @endif
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">Belum punya akun?
                            <a href="{{ route('register') }}" class="text-primary fw-semibold">Daftar sekarang</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
