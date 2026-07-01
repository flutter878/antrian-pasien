<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Sistem Pendaftaran Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); min-height: 100vh; }
        .card { border: none; border-radius: 1rem; box-shadow: 0 1rem 3rem rgba(0,0,0,.2); }
        .card-header { background: #0d6efd; color: #fff; border-radius: 1rem 1rem 0 0 !important; padding: 1.5rem; }
    </style>
</head>
<body class="d-flex align-items-center py-4">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header text-center">
                    <i class="bi bi-hospital fs-2 d-block mb-2"></i>
                    <h4 class="mb-0 fw-bold">Daftar Akun Pasien</h4>
                    <p class="mb-0 small opacity-75">Sistem Informasi Pendaftaran Pasien Online</p>
                </div>
                <div class="card-body p-4">

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Masukkan nama lengkap" required autofocus>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">NIK (16 digit) <span class="text-danger">*</span></label>
                                <input type="text" name="nik" value="{{ old('nik') }}"
                                    class="form-control @error('nik') is-invalid @enderror"
                                    placeholder="Nomor Induk Kependudukan" maxlength="16" pattern="[0-9]{16}" required>
                                @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="contoh@email.com" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor HP <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                                class="form-control @error('no_hp') is-invalid @enderror"
                                placeholder="Contoh: 08123456789" required>
                            @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat" rows="2"
                                class="form-control @error('alamat') is-invalid @enderror"
                                placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Minimal 8 karakter" required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation"
                                    class="form-control"
                                    placeholder="Ulangi password" required>
                            </div>
                        </div>

                        <div class="d-grid mt-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-primary fw-semibold">Masuk di sini</a>
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
