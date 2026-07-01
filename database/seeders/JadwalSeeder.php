<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\JadwalPraktek;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $dokter1 = Dokter::where('spesialisasi', 'Penyakit Dalam')->first();
        $dokter2 = Dokter::where('spesialisasi', 'Spesialis Anak')->first();
        $dokter3 = Dokter::where('spesialisasi', 'Obstetri & Ginekologi')->first();

        // Jadwal dr. Ahmad Fauzi (Penyakit Dalam)
        $jadwalDokter1 = [
            ['hari' => 'Senin',  'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'kuota' => 20],
            ['hari' => 'Rabu',   'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'kuota' => 15],
            ['hari' => 'Jumat',  'jam_mulai' => '08:00', 'jam_selesai' => '11:00', 'kuota' => 15],
        ];

        // Jadwal dr. Siti Rahayu (Anak)
        $jadwalDokter2 = [
            ['hari' => 'Selasa',  'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'kuota' => 20],
            ['hari' => 'Kamis',   'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'kuota' => 20],
            ['hari' => 'Sabtu',   'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'kuota' => 25],
        ];

        // Jadwal dr. Budi Santoso (OG)
        $jadwalDokter3 = [
            ['hari' => 'Senin',  'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'kuota' => 15],
            ['hari' => 'Rabu',   'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'kuota' => 15],
            ['hari' => 'Sabtu',  'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'kuota' => 15],
        ];

        foreach ($jadwalDokter1 as $j) {
            JadwalPraktek::create(array_merge($j, ['dokter_id' => $dokter1->id, 'aktif' => true]));
        }
        foreach ($jadwalDokter2 as $j) {
            JadwalPraktek::create(array_merge($j, ['dokter_id' => $dokter2->id, 'aktif' => true]));
        }
        foreach ($jadwalDokter3 as $j) {
            JadwalPraktek::create(array_merge($j, ['dokter_id' => $dokter3->id, 'aktif' => true]));
        }
    }
}
