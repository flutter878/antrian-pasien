@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard
        </h4>
        <small class="text-muted">Selamat datang, {{ auth()->user()->name }} • {{ now()->translatedFormat('l, d F Y') }}</small>
    </div>
</div>

{{-- Quick Navigation untuk Pasien --}}
@if(auth()->user()->isPasien())
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-4">
        <a href="{{ route('pasien.dokter') }}" class="btn btn-primary w-100 py-3 d-flex flex-column align-items-center gap-2">
            <i class="bi bi-calendar-plus fs-5"></i>
            <span>Daftar Antrian</span>
        </a>
    </div>
    <div class="col-sm-6 col-md-4">
        <a href="{{ route('pasien.riwayat') }}" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-2">
            <i class="bi bi-clock-history fs-5"></i>
            <span>Riwayat Kunjungan</span>
        </a>
    </div>
    <div class="col-sm-6 col-md-4">
        <a href="{{ route('notifications.index') }}" class="btn btn-outline-info w-100 py-3 d-flex flex-column align-items-center gap-2">
            <i class="bi bi-bell fs-5"></i>
            <span>Notifikasi</span>
        </a>
    </div>
</div>
@endif

{{-- Quick Navigation untuk Admin --}}
@if(auth()->user()->isAdmin())
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.antrian') }}" class="btn btn-primary w-100 py-3 d-flex flex-column align-items-center gap-2">
            <i class="bi bi-list-ol fs-5"></i>
            <span>Antrian Hari Ini</span>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.dokter.index') }}" class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center gap-2">
            <i class="bi bi-person-badge fs-5"></i>
            <span>Kelola Dokter</span>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-warning w-100 py-3 d-flex flex-column align-items-center gap-2">
            <i class="bi bi-calendar3 fs-5"></i>
            <span>Jadwal Praktek</span>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('notifications.index') }}" class="btn btn-outline-info w-100 py-3 d-flex flex-column align-items-center gap-2">
            <i class="bi bi-bell fs-5"></i>
            <span>Notifikasi</span>
        </a>
    </div>
</div>
@endif

{{-- Feature Cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-light border-0">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-info-circle text-info me-2"></i>Tentang Aplikasi
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Aplikasi Antrian Pasien adalah sistem manajemen antrian modern untuk rumah sakit dan klinik.
                    Sistem ini membantu mengelola pendaftaran pasien, jadwal dokter, dan antrian secara efisien.
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge bg-primary"><i class="bi bi-check me-1"></i>Real-time Queue</span>
                    <span class="badge bg-success"><i class="bi bi-check me-1"></i>Doctor Scheduling</span>
                    <span class="badge bg-info"><i class="bi bi-check me-1"></i>Notifications</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-light border-0">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-question-circle text-warning me-2"></i>Bantuan Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-2 small">
                    @if(auth()->user()->isPasien())
                    <div>
                        <strong>📋 Cara Daftar Antrian:</strong>
                        <span class="text-muted d-block">Pilih dokter → Pilih jadwal praktek → Isi form pendaftaran → Selesai!</span>
                    </div>
                    <div>
                        <strong>🔔 Notifikasi:</strong>
                        <span class="text-muted d-block">Anda akan menerima notifikasi saat antrian dipanggil</span>
                    </div>
                    @elseif(auth()->user()->isAdmin())
                    <div>
                        <strong>👨‍⚕️ Kelola Dokter:</strong>
                        <span class="text-muted d-block">Tambah, edit, atau hapus data dokter</span>
                    </div>
                    <div>
                        <strong>📅 Jadwal Praktek:</strong>
                        <span class="text-muted d-block">Atur jadwal dan kuota praktek dokter</span>
                    </div>
                    <div>
                        <strong>📊 Kelola Antrian:</strong>
                        <span class="text-muted d-block">Update status dan monitor antrian real-time</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Profile Card --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-person-circle me-2"></i>Profil Pengguna
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 border-end text-center py-3">
                <div class="avatar-placeholder mb-3">
                    <i class="bi bi-person-circle text-primary" style="font-size: 3rem;"></i>
                </div>
                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                <div class="badge bg-{{ auth()->user()->isAdmin() ? 'danger' : 'success' }}">
                    {{ auth()->user()->isAdmin() ? 'Administrator' : 'Pasien' }}
                </div>
            </div>
            <div class="col-md-9 py-3">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="small text-muted">Email</div>
                        <div class="fw-semibold text-break">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="small text-muted">Nomor Telepon</div>
                        <div class="fw-semibold">{{ auth()->user()->no_hp ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="small text-muted">NIK</div>
                        <div class="fw-semibold">{{ auth()->user()->nik ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="small text-muted">Alamat</div>
                        <div class="fw-semibold">{{ auth()->user()->alamat ?? '-' }}</div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Ubah Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
