<?php

namespace Database\Seeders;

use App\Models\Dokter;
use Illuminate\Database\Seeder;

class DokterSeeder extends Seeder
{
    public function run(): void
    {
        $dokter = [
            [
                'nama'         => 'dr. Ahmad Fauzi, Sp.PD',
                'spesialisasi' => 'Penyakit Dalam',
                'bio'          => 'Dokter spesialis penyakit dalam dengan pengalaman lebih dari 10 tahun. Berpengalaman menangani diabetes, hipertensi, dan penyakit metabolik lainnya.',
                'foto'         => null,
            ],
            [
                'nama'         => 'dr. Siti Rahayu, Sp.A',
                'spesialisasi' => 'Spesialis Anak',
                'bio'          => 'Dokter spesialis anak yang berdedikasi tinggi dalam memberikan pelayanan kesehatan untuk anak-anak. Berpengalaman dalam tumbuh kembang anak dan imunisasi.',
                'foto'         => null,
            ],
            [
                'nama'         => 'dr. Budi Santoso, Sp.OG',
                'spesialisasi' => 'Obstetri & Ginekologi',
                'bio'          => 'Dokter spesialis kebidanan dan kandungan dengan keahlian dalam perawatan kehamilan, persalinan normal dan caesar, serta kesehatan reproduksi wanita.',
                'foto'         => null,
            ],
        ];

        foreach ($dokter as $d) {
            Dokter::create($d);
        }
    }
}
