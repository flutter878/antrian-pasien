@extends('layouts.app')

@section('title', 'Kelola Jadwal Praktek')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-calendar-week me-2 text-success"></i>Kelola Jadwal Praktek
        </h4>
        <small class="text-muted">Tambah, ubah, atau nonaktifkan jadwal dokter.</small>
    </div>
    <div class="col-auto d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Jadwal
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.jadwal.index') }}" class="row g-2 align-items-end">
            <div class="col-sm-4 col-lg-3">
                <label class="form-label small mb-1 fw-semibold">Filter Dokter</label>
                <select name="dokter_id" class="form-select form-select-sm">
                    <option value="">Semua Dokter</option>
                    @foreach($dokter as $d)
                    <option value="{{ $d->id }}" {{ $dokterId == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-3">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($jadwal->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Dokter</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Kuota</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwal as $item)
                    <tr>
                        <td class="ps-3 fw-semibold">{{ $item->dokter->nama }}</td>
                        <td>{{ $item->hari }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($item->jam_selesai)->format('H:i') }}</td>
                        <td>{{ $item->kuota }}</td>
                        <td>
                            <span class="badge bg-{{ $item->aktif ? 'success' : 'secondary' }}">
                                {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.jadwal.edit', $item) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.jadwal.toggle', $item) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-{{ $item->aktif ? 'warning' : 'success' }}" title="{{ $item->aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi bi-toggle-{{ $item->aktif ? 'on' : 'off' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.jadwal.destroy', $item) }}" onsubmit="return confirm('Hapus jadwal ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
            Tidak ada jadwal.
        </div>
        @endif
    </div>
</div>
@endsection
