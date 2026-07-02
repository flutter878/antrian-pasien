@extends('layouts.app')

@section('title', 'Edit Dokter')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Dokter
        </h4>
        <small class="text-muted">{{ $dokter->nama }}</small>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.dokter.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.dokter.update', $dokter) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Dokter <span class="text-danger">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $dokter->nama) }}"
                               class="form-control @error('nama') is-invalid @enderror">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Spesialisasi <span class="text-danger">*</span></label>
                        <input type="text" name="spesialisasi" value="{{ old('spesialisasi', $dokter->spesialisasi) }}"
                               class="form-control @error('spesialisasi') is-invalid @enderror">
                        @error('spesialisasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bio / Deskripsi</label>
                        <textarea name="bio" rows="4"
                                  class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $dokter->bio) }}</textarea>
                        @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Dokter</label>

                        {{-- Foto saat ini --}}
                        @if($dokter->foto)
                        <div class="mb-2 d-flex align-items-center gap-3">
                            <img src="{{ $dokter->foto_url }}" alt="{{ $dokter->nama }}"
                                 class="rounded" style="height:70px;object-fit:cover;">
                            <small class="text-muted">Foto saat ini</small>
                        </div>
                        @endif

                        <input type="file" name="foto" accept="image/*"
                               class="form-control @error('foto') is-invalid @enderror"
                               onchange="previewFoto(this)">
                        @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Format: JPG, JPEG, PNG, WEBP. Maks 2MB.</small>
                        <div class="mt-2">
                            <img id="preview" src="" alt="Preview"
                                 class="rounded d-none" style="height:100px;object-fit:cover;">
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.dokter.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i>Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewFoto(input) {
    const preview = document.getElementById('preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
