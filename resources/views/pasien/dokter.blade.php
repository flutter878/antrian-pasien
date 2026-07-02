@extends('layouts.app')

@section('title', 'Pilih Dokter')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-person-badge me-2 text-primary"></i>Pilih Dokter
        </h4>
        <small class="text-muted">Pilih dokter dan jadwal yang tersedia untuk mendaftar antrian</small>
    </div>
    <div class="col-auto">
        <a href="{{ route('pasien.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

@if($dokter->isEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-person-x fs-1 text-muted d-block mb-3"></i>
        <p class="text-muted">Belum ada dokter yang tersedia saat ini.</p>
    </div>
</div>
@else
<div class="row g-4">
    @foreach($dokter as $d)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                {{-- Foto & Info Dokter --}}
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width: 64px; height: 64px; flex-shrink: 0;">
                        @if($d->foto)
                            <img src="{{ $d->foto_url }}" alt="{{ $d->nama }}"
                                 class="rounded-circle" style="width:64px;height:64px;object-fit:cover;">
                        @else
                            <i class="bi bi-person-fill fs-2 text-primary"></i>
                        @endif
                    </div>
                    <div>
                        <div class="fw-bold">{{ $d->nama }}</div>
                        <span class="badge bg-info text-dark">{{ $d->spesialisasi }}</span>
                    </div>
                </div>

                @if($d->bio)
                <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $d->bio }}
                </p>
                @endif

                {{-- Jadwal Praktek --}}
                <div class="mb-3">
                    <div class="fw-semibold small mb-2">
                        <i class="bi bi-calendar-week me-1 text-primary"></i>Jadwal Praktek
                    </div>
                    @if($d->jadwalPraktek->isNotEmpty())
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($d->jadwalPraktek as $jadwal)
                        <span class="badge bg-light text-dark border small">
                            {{ $jadwal->hari }}
                            <span class="text-muted">
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </span>
                        </span>
                        @endforeach
                    </div>
                    @else
                    <small class="text-muted">Belum ada jadwal aktif</small>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-3">
                @if($d->jadwalPraktek->isNotEmpty())
                <a href="{{ route('pasien.daftar', $d) }}" class="btn btn-primary w-100">
                    <i class="bi bi-calendar-plus me-1"></i>Daftar Antrian
                </a>
                @else
                <button class="btn btn-secondary w-100" disabled>
                    <i class="bi bi-calendar-x me-1"></i>Tidak Ada Jadwal
                </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
