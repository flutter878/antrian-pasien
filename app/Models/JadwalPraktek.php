<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalPraktek extends Model
{
    use HasFactory;

    protected $table = 'jadwal_praktek';

    protected $fillable = [
        'dokter_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kuota',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Urutan hari untuk sorting
    public static array $urutanHari = [
        'Senin' => 1,
        'Selasa' => 2,
        'Rabu' => 3,
        'Kamis' => 4,
        'Jumat' => 5,
        'Sabtu' => 6,
        'Minggu' => 7,
    ];

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'jadwal_id');
    }

    /**
     * Hitung sisa kuota untuk tanggal tertentu
     */
    public function sisaKuota(string $tanggal): int
    {
        $terpakai = $this->pendaftaran()
            ->whereDate('tanggal_daftar', $tanggal)
            ->whereIn('status', ['menunggu', 'dipanggil', 'selesai'])
            ->count();

        return max(0, $this->kuota - $terpakai);
    }

    /**
     * Cek apakah tanggal sesuai dengan hari jadwal ini
     */
    public function sesuaiTanggal(string $tanggal): bool
    {
        $hariIndonesia = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $hariTanggal = $hariIndonesia[date('l', strtotime($tanggal))];
        return $hariTanggal === $this->hari;
    }
}
