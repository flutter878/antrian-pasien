@extends('layouts.app')

@section('title', 'Daftar Antrian - ' . $dokter->nama)

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-calendar-plus me-2 text-primary"></i>Daftar Antrian
        </h4>
        <small class="text-muted">Pilih jadwal dan isi keluhan untuk mendaftar</small>
    </div>
    <div class="col-auto">
        <a href="{{ route('pasien.dokter') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Info Dokter --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 80px; height: 80px;">
                    @if($dokter->foto)
                        <img src="{{ $dokter->foto_url }}" alt="{{ $dokter->nama }}"
                             class="rounded-circle" style="width:80px;height:80px;object-fit:cover;">
                    @else
                        <i class="bi bi-person-fill fs-1 text-primary"></i>
                    @endif
                </div>
                <h5 class="fw-bold mb-1">{{ $dokter->nama }}</h5>
                <span class="badge bg-info text-dark mb-3">{{ $dokter->spesialisasi }}</span>
                @if($dokter->bio)
                <p class="text-muted small mb-0">{{ $dokter->bio }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Form Pendaftaran --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <span class="fw-semibold"><i class="bi bi-calendar-check me-2 text-primary"></i>Pilih Jadwal & Tanggal</span>
            </div>
            <div class="card-body">

                @if(count($tanggalTersedia) === 0)
                <div class="text-center py-4">
                    <i class="bi bi-calendar-x fs-2 text-muted d-block mb-3"></i>
                    <p class="text-muted mb-0">Tidak ada jadwal tersedia dalam 14 hari ke depan.</p>
                    <small class="text-muted">Semua kuota sudah penuh atau belum ada jadwal aktif.</small>
                </div>
                @else
                <form action="{{ route('pasien.daftar.store', $dokter) }}" method="POST">
                    @csrf

                    {{-- Pilih Jadwal/Tanggal --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih Tanggal & Jadwal <span class="text-danger">*</span></label>
                        @error('jadwal_id') <div class="text-danger small mb-2">{{ $message }}</div> @enderror
                        @error('tanggal_daftar') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

                        <div class="row g-2">
                            @foreach($tanggalTersedia as $item)
                            <div class="col-12">
                                <input type="radio" class="btn-check" name="jadwal_tanggal_combo"
                                       id="jadwal_{{ $loop->index }}"
                                       value="{{ $item['jadwal_id'] }}|{{ $item['tanggal'] }}"
                                       {{ old('jadwal_tanggal_combo') == $item['jadwal_id'].'|'.$item['tanggal'] ? 'checked' : '' }}
                                       required>
                                <label class="btn btn-outline-primary w-100 text-start py-3 px-3" for="jadwal_{{ $loop->index }}">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="fw-semibold">{{ $item['label'] }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($item['jam_mulai'])->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($item['jam_selesai'])->format('H:i') }} WIB
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge {{ $item['sisa_kuota'] <= 5 ? 'bg-warning text-dark' : 'bg-success' }}">
                                                Sisa {{ $item['sisa_kuota'] }} / {{ $item['kuota'] }}
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        {{-- Hidden inputs yang diisi via JS --}}
                        <input type="hidden" name="jadwal_id" id="input_jadwal_id">
                        <input type="hidden" name="tanggal_daftar" id="input_tanggal_daftar">
                    </div>

                    {{-- Keluhan --}}
                    <div class="mb-4">
                        <label for="keluhan" class="form-label fw-semibold">Keluhan <span class="text-muted fw-normal">(opsional)</span></label>
                        <textarea name="keluhan" id="keluhan" rows="3"
                                  class="form-control @error('keluhan') is-invalid @enderror"
                                  placeholder="Tuliskan keluhan atau gejala yang Anda rasakan..."
                                  maxlength="500">{{ old('keluhan') }}</textarea>
                        @error('keluhan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maksimal 500 karakter</small>
                    </div>

                    {{-- Info --}}
                    <div class="alert alert-info py-2 mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Nomor antrian akan diberikan secara otomatis setelah pendaftaran berhasil.
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('pasien.dokter') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-1"></i>Daftar Sekarang
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Saat radio button dipilih, isi hidden inputs
    document.querySelectorAll('input[name="jadwal_tanggal_combo"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const parts = this.value.split('|');
            document.getElementById('input_jadwal_id').value = parts[0];
            document.getElementById('input_tanggal_daftar').value = parts[1];
        });
    });

    // Set nilai awal jika ada old value
    const checked = document.querySelector('input[name="jadwal_tanggal_combo"]:checked');
    if (checked) {
        const parts = checked.value.split('|');
        document.getElementById('input_jadwal_id').value = parts[0];
        document.getElementById('input_tanggal_daftar').value = parts[1];
    }
</script>
@endpush
