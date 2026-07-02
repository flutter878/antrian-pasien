@extends('layouts.app')

@section('title', 'Riwayat Pendaftaran')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pendaftaran
        </h4>
        <small class="text-muted">Semua riwayat antrian Anda</small>
    </div>
    <div class="col-auto">
        <a href="{{ route('pasien.dokter') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Daftar Baru
        </a>
    </div>
</div>

{{-- Filter Status --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2">
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <span class="text-muted small me-1">Filter:</span>
            @php
                $statusList = [
                    'semua'     => ['label' => 'Semua',    'class' => 'btn-outline-secondary'],
                    'menunggu'  => ['label' => 'Menunggu', 'class' => 'btn-outline-warning'],
                    'dipanggil' => ['label' => 'Dipanggil','class' => 'btn-outline-info'],
                    'selesai'   => ['label' => 'Selesai',  'class' => 'btn-outline-success'],
                    'batal'     => ['label' => 'Batal',    'class' => 'btn-outline-danger'],
                ];
                $aktifStatus = request('status', 'semua');
            @endphp

            @foreach($statusList as $key => $val)
            <a href="{{ route('pasien.riwayat', ['status' => $key]) }}"
               class="btn btn-sm {{ $aktifStatus === $key ? str_replace('outline-', '', $val['class']) . ' text-white' : $val['class'] }}">
                {{ $val['label'] }}
            </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Tabel Riwayat --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($riwayat->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>Dokter</th>
                        <th>Spesialisasi</th>
                        <th>Jam Praktek</th>
                        <th>No. Antrian</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayat as $item)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-semibold">{{ \Carbon\Carbon::parse($item->tanggal_daftar)->translatedFormat('d M Y') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal_daftar)->translatedFormat('l') }}</small>
                        </td>
                        <td>{{ $item->dokter->nama }}</td>
                        <td class="text-muted">{{ $item->dokter->spesialisasi }}</td>
                        <td class="text-muted small">
                            {{ \Carbon\Carbon::parse($item->jadwal->jam_mulai)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($item->jadwal->jam_selesai)->format('H:i') }}
                        </td>
                        <td>
                            <span class="badge bg-primary rounded-pill px-3 fs-6">{{ $item->no_antrian }}</span>
                        </td>
                        <td>
                            @if($item->keluhan)
                            <span class="text-muted small" title="{{ $item->keluhan }}"
                                  style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; max-width: 150px;">
                                {{ $item->keluhan }}
                            </span>
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->status_badge }}">
                                {{ $item->status_label }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($item->status === 'menunggu')
                            <form action="{{ route('pasien.batal', $item) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin membatalkan antrian ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </button>
                            </form>
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($riwayat->hasPages())
        <div class="d-flex justify-content-center py-3">
            {{ $riwayat->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <p class="mb-2">Tidak ada riwayat pendaftaran
                @if($aktifStatus !== 'semua')
                dengan status <strong>{{ $statusList[$aktifStatus]['label'] }}</strong>
                @endif
            </p>
            @if($aktifStatus !== 'semua')
            <a href="{{ route('pasien.riwayat') }}" class="btn btn-sm btn-outline-secondary">Tampilkan Semua</a>
            @else
            <a href="{{ route('pasien.dokter') }}" class="btn btn-sm btn-primary">Daftar Antrian Sekarang</a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
