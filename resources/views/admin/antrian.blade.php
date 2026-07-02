@extends('layouts.app')

@section('title', 'Kelola Antrian')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-list-ol me-2 text-primary"></i>Kelola Antrian
        </h4>
        <small class="text-muted">Kelola dan update status antrian pasien</small>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.antrian') }}" class="row g-2 align-items-end">
            <div class="col-sm-4 col-lg-3">
                <label class="form-label small mb-1 fw-semibold">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                       class="form-control form-control-sm">
            </div>
            <div class="col-sm-4 col-lg-3">
                <label class="form-label small mb-1 fw-semibold">Dokter</label>
                <select name="dokter_id" class="form-select form-select-sm">
                    <option value="">Semua Dokter</option>
                    @foreach($dokter as $d)
                    <option value="{{ $d->id }}" {{ $dokterId == $d->id ? 'selected' : '' }}>
                        {{ $d->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-4 col-lg-2">
                <label class="form-label small mb-1 fw-semibold">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="menunggu"  {{ $status === 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="dipanggil" {{ $status === 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                    <option value="selesai"   {{ $status === 'selesai'   ? 'selected' : '' }}>Selesai</option>
                    <option value="batal"     {{ $status === 'batal'     ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-3">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('admin.antrian') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Statistik Filter --}}
@if($antrian->isNotEmpty())
<div class="row g-2 mb-3">
    @php
        $counts = $antrian->groupBy('status');
    @endphp
    <div class="col-auto">
        <span class="badge bg-secondary fs-6">Total: {{ $antrian->count() }}</span>
    </div>
    <div class="col-auto">
        <span class="badge bg-warning text-dark fs-6">Menunggu: {{ $counts->get('menunggu', collect())->count() }}</span>
    </div>
    <div class="col-auto">
        <span class="badge bg-info fs-6">Dipanggil: {{ $counts->get('dipanggil', collect())->count() }}</span>
    </div>
    <div class="col-auto">
        <span class="badge bg-success fs-6">Selesai: {{ $counts->get('selesai', collect())->count() }}</span>
    </div>
    <div class="col-auto">
        <span class="badge bg-danger fs-6">Batal: {{ $counts->get('batal', collect())->count() }}</span>
    </div>
</div>
@endif

{{-- Tabel Antrian --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($antrian->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. Antrian</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Jam Praktek</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th class="text-center">Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($antrian as $item)
                    <tr class="{{ $item->status === 'dipanggil' ? 'table-info' : ($item->status === 'selesai' ? 'table-light' : '') }}">
                        <td class="ps-3">
                            <span class="badge bg-primary rounded-pill px-3 fs-6">{{ $item->no_antrian }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $item->user->name }}</div>
                            <small class="text-muted">{{ $item->user->no_hp ?? '-' }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $item->dokter->nama }}</div>
                            <small class="text-muted">{{ $item->dokter->spesialisasi }}</small>
                        </td>
                        <td class="text-muted small">
                            {{ \Carbon\Carbon::parse($item->jadwal->jam_mulai)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($item->jadwal->jam_selesai)->format('H:i') }}
                        </td>
                        <td>
                            @if($item->keluhan)
                            <span class="text-muted small" style="max-width:160px;display:block;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;"
                                  title="{{ $item->keluhan }}">
                                {{ $item->keluhan }}
                            </span>
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->status_badge }} fs-6">
                                {{ $item->status_label }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if(!in_array($item->status, ['selesai','batal']))
                            <div class="btn-group btn-group-sm">
                                @if($item->status === 'menunggu')
                                <form method="POST" action="{{ route('admin.antrian.status', $item) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="dipanggil">
                                    <button type="submit" class="btn btn-info text-white" title="Panggil">
                                        <i class="bi bi-megaphone"></i> Panggil
                                    </button>
                                </form>
                                @elseif($item->status === 'dipanggil')
                                <form method="POST" action="{{ route('admin.antrian.status', $item) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="selesai">
                                    <button type="submit" class="btn btn-success" title="Selesai">
                                        <i class="bi bi-check-lg"></i> Selesai
                                    </button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('admin.antrian.status', $item) }}"
                                      onsubmit="return confirm('Batalkan antrian ini?')">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="batal">
                                    <button type="submit" class="btn btn-outline-danger" title="Batal">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <p class="mb-0">Tidak ada antrian untuk filter yang dipilih.</p>
        </div>
        @endif
    </div>
</div>
@endsection
