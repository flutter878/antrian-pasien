@extends('layouts.app')

@section('title', 'Edit Jadwal Praktek')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-pencil me-2 text-success"></i>Edit Jadwal Praktek
        </h4>
        <small class="text-muted">Ubah detail jadwal dokter.</small>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.jadwal.update', $jadwal) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Dokter</label>
                <select name="dokter_id" class="form-select @error('dokter_id') is-invalid @enderror">
                    <option value="">Pilih Dokter</option>
                    @foreach($dokter as $d)
                    <option value="{{ $d->id }}" {{ old('dokter_id', $jadwal->dokter_id) == $d->id ? 'selected' : '' }}>
                        {{ $d->nama }} - {{ $d->spesialisasi }}
                    </option>
                    @endforeach
                </select>
                @error('dokter_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Hari</label>
                    <select name="hari" class="form-select @error('hari') is-invalid @enderror">
                        <option value="">Pilih Hari</option>
                        @foreach($hariList as $hari)
                        <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                        @endforeach
                    </select>
                    @error('hari')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" class="form-control @error('jam_mulai') is-invalid @enderror">
                    @error('jam_mulai')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" class="form-control @error('jam_selesai') is-invalid @enderror">
                    @error('jam_selesai')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <label class="form-label">Kuota</label>
                    <input type="number" name="kuota" value="{{ old('kuota', $jadwal->kuota) }}" min="1" class="form-control @error('kuota') is-invalid @enderror">
                    @error('kuota')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="aktif" id="aktif" {{ old('aktif', $jadwal->aktif) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Aktif</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Perbarui Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
