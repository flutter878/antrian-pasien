@extends('layouts.app')

@section('title', 'Kelola Dokter')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-person-badge me-2 text-primary"></i>Kelola Dokter
        </h4>
        <small class="text-muted">Daftar semua dokter yang terdaftar</small>
    </div>
    <div class="col-auto d-flex align-items-center gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <a href="{{ route('admin.dokter.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Dokter
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($dokter->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width:60px">#</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Spesialisasi</th>
                        <th class="text-center">Jadwal Aktif</th>
                        <th>Bio</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dokter as $d)
                    <tr>
                        <td class="ps-3 text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center overflow-hidden"
                                 style="width:44px;height:44px;">
                                @if($d->foto)
                                <img src="{{ $d->foto_url }}" alt="{{ $d->nama }}"
                                     style="width:44px;height:44px;object-fit:cover;">
                                @else
                                <i class="bi bi-person-fill text-primary"></i>
                                @endif
                            </div>
                        </td>
                        <td class="fw-semibold">{{ $d->nama }}</td>
                        <td><span class="badge bg-info text-dark">{{ $d->spesialisasi }}</span></td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $d->jadwal_praktek_count }}</span>
                        </td>
                        <td class="text-muted small" style="max-width:200px;">
                            <div style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                                {{ $d->bio ?? '-' }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.dokter.edit', $d) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.dokter.destroy', $d) }}"
                                      onsubmit="return confirm('Hapus dokter {{ $d->nama }}? Semua jadwal terkait juga akan terhapus.')">
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
            <i class="bi bi-person-x fs-1 d-block mb-3"></i>
            <p class="mb-3">Belum ada dokter yang terdaftar.</p>
            <a href="{{ route('admin.dokter.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Dokter Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
