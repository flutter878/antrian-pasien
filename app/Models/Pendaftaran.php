<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'user_id',
        'dokter_id',
        'jadwal_id',
        'tanggal_daftar',
        'no_antrian',
        'status',
        'keluhan',
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(JadwalPraktek::class, 'jadwal_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'menunggu'  => 'warning',
            'dipanggil' => 'info',
            'selesai'   => 'success',
            'batal'     => 'danger',
            default     => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu'  => 'Menunggu',
            'dipanggil' => 'Dipanggil',
            'selesai'   => 'Selesai',
            'batal'     => 'Batal',
            default     => 'Unknown',
        };
    }
}
