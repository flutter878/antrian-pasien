@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Admin
        </h4>
        <small class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</small>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.antrian') }}" class="btn btn-primary">
            <i class="bi bi-list-ol me-1"></i>Kelola Antrian Hari Ini
        </a>
    </div>
</div>

{{-- Statistik Hari Ini --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-calendar-check fs-3 text-primary"></i>
                </div>
                <div>
                    <div class="fw-bold fs-2">{{ $totalAntrian }}</div>
                    <div class="text-muted small">Antrian Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-hourglass-split fs-3 text-warning"></i>
                </div>
                <div>
                    <div class="fw-bold fs-2">{{ $totalMenunggu }}</div>
                    <div class="text-muted small">Menunggu</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-check-circle fs-3 text-success"></i>
                </div>
                <div>
                    <div class="fw-bold fs-2">{{ $totalSelesai }}</div>
                    <div class="text-muted small">Selesai</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-info bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-people fs-3 text-info"></i>
                </div>
                <div>
                    <div class="fw-bold fs-2">{{ $totalPasien }}</div>
                    <div class="text-muted small">Total Pasien</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Antrian Per Dokter Hari Ini --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                <span class="fw-semibold"><i class="bi bi-person-badge me-2 text-primary"></i>Antrian Per Dokter Hari Ini</span>
                <span class="badge bg-light text-dark">{{ now()->translatedFormat('d M Y') }}</span>
            </div>
            <div class="card-body p-0">
                @if($antrianPerDokter->where('total_hari_ini', '>', 0)->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Dokter</th>
                                <th>Spesialisasi</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Menunggu</th>
                                <th class="text-center">Selesai</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($antrianPerDokter as $d)
                            @if($d->total_hari_ini > 0)
                            <tr>
                                <td class="ps-3 fw-semibold">{{ $d->nama }}</td>
                                <td class="text-muted small">{{ $d->spesialisasi }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $d->total_hari_ini }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark">{{ $d->menunggu }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $d->selesai }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.antrian', ['dokter_id' => $d->id]) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                    Belum ada antrian hari ini.
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Antrian Terbaru & Akses Cepat --}}
    <div class="col-lg-5">
        {{-- Akses Cepat --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <span class="fw-semibold"><i class="bi bi-lightning me-2 text-warning"></i>Akses Cepat</span>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('admin.antrian') }}" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-1">
                            <i class="bi bi-list-ol fs-4"></i>
                            <small>Kelola Antrian</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.dokter.index') }}" class="btn btn-outline-info w-100 py-3 d-flex flex-column align-items-center gap-1">
                            <i class="bi bi-person-badge fs-4"></i>
                            <small>Kelola Dokter</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center gap-1">
                            <i class="bi bi-calendar-week fs-4"></i>
                            <small>Kelola Jadwal</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.antrian', ['tanggal' => now()->addDay()->toDateString()]) }}"
                           class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center gap-1">
                            <i class="bi bi-calendar-plus fs-4"></i>
                            <small>Antrian Besok</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Antrian Terbaru --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <span class="fw-semibold"><i class="bi bi-clock me-2 text-primary"></i>Antrian Terbaru</span>
            </div>
            <div class="card-body p-0">
                @if($antrianTerbaru->isNotEmpty())
                @foreach($antrianTerbaru as $a)
                <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                    <span class="badge bg-primary rounded-pill px-2 fs-6">{{ $a->no_antrian }}</span>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold text-truncate small">{{ $a->user->name }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $a->dokter->nama }}</div>
                    </div>
                    <span class="badge bg-{{ $a->status_badge }} flex-shrink-0">{{ $a->status_label }}</span>
                </div>
                @endforeach
                <div class="text-center py-2">
                    <a href="{{ route('admin.antrian') }}" class="small text-primary">Lihat semua →</a>
                </div>
                @else
                <div class="text-center py-4 text-muted small">Belum ada antrian.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
