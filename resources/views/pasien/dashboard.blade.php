@extends('layouts.app')

@section('title', 'Dashboard Pasien')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard
        </h4>
        <small class="text-muted">Selamat datang, {{ auth()->user()->name }}</small>
    </div>
    <div class="col-auto">
        <a href="{{ route('pasien.dokter') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Daftar Antrian
        </a>
    </div>
</div>

{{-- Statistik --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card h-100 border-start border-primary border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-clipboard-list fs-3 text-primary"></i>
                </div>
                <div>
                    <div class="fw-bold fs-3">{{ $totalPendaftaran }}</div>
                    <div class="text-muted small">Total Pendaftaran</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card h-100 border-start border-success border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-check-circle fs-3 text-success"></i>
                </div>
                <div>
                    <div class="fw-bold fs-3">{{ $totalSelesai }}</div>
                    <div class="text-muted small">Selesai Diperiksa</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card h-100 border-start border-warning border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-hourglass-split fs-3 text-warning"></i>
                </div>
                <div>
                    <div class="fw-bold fs-3">{{ $totalMenunggu }}</div>
                    <div class="text-muted small">Sedang Menunggu</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Antrian Aktif Hari Ini --}}
@if($antrianAktif)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white d-flex align-items-center gap-2">
                <i class="bi bi-person-lines-fill"></i>
                <span class="fw-semibold">Antrian Anda Hari Ini</span>
                <span class="badge {{ $antrianAktif->status === 'dipanggil' ? 'bg-warning text-dark' : 'bg-light text-primary' }} ms-auto">
                    {{ $antrianAktif->status_label }}
                </span>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center border-end">
                        <div class="antrian-number">{{ $antrianAktif->no_antrian }}</div>
                        <div class="text-muted small">Nomor Antrian</div>
                        @if($posisiAntrian)
                        <div class="badge bg-info mt-1">Posisi ke-{{ $posisiAntrian }}</div>
                        @endif
                    </div>
                    <div class="col-md-9 ps-md-4 mt-3 mt-md-0">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="text-muted small">Dokter</div>
                                <div class="fw-semibold">{{ $antrianAktif->dokter->nama }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small">Spesialisasi</div>
                                <div class="fw-semibold">{{ $antrianAktif->dokter->spesialisasi }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small">Tanggal</div>
                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($antrianAktif->tanggal_daftar)->translatedFormat('d F Y') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small">Jam Praktek</div>
                                <div class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($antrianAktif->jadwal->jam_mulai)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($antrianAktif->jadwal->jam_selesai)->format('H:i') }}
                                </div>
                            </div>
                            @if($antrianAktif->keluhan)
                            <div class="col-12">
                                <div class="text-muted small">Keluhan</div>
                                <div class="fw-semibold">{{ $antrianAktif->keluhan }}</div>
                            </div>
                            @endif
                        </div>

                        @if($antrianAktif->status === 'dipanggil')
                        <div class="alert alert-warning mt-3 mb-0 py-2">
                            <i class="bi bi-megaphone me-1"></i>
                            <strong>Anda sedang dipanggil!</strong> Segera menuju ruang periksa.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <i class="bi bi-calendar-x fs-1 text-muted mb-2 d-block"></i>
                <p class="text-muted mb-3">Anda tidak memiliki antrian aktif hari ini.</p>
                <a href="{{ route('pasien.dokter') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Riwayat Pendaftaran --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
        <span class="fw-semibold"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pendaftaran</span>
    </div>
    <div class="card-body p-0">
        @if($riwayat->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>Dokter</th>
                        <th>Spesialisasi</th>
                        <th>No. Antrian</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayat as $item)
                    <tr>
                        <td class="ps-3">
                            {{ \Carbon\Carbon::parse($item->tanggal_daftar)->translatedFormat('d M Y') }}
                        </td>
                        <td>{{ $item->dokter->nama }}</td>
                        <td class="text-muted">{{ $item->dokter->spesialisasi }}</td>
                        <td>
                            <span class="badge bg-primary rounded-pill px-3">{{ $item->no_antrian }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->status_badge }} badge-status">
                                {{ $item->status_label }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4 text-muted">
            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
            Belum ada riwayat pendaftaran.
        </div>
        @endif
    </div>
</div>
@endsection
