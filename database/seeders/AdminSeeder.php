<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@antrian-pasien.test',
            'password' => Hash::make('password'),
            'nik'      => '0000000000000000',
            'no_hp'    => '081234567890',
            'alamat'   => 'Jl. Klinik No. 1, Jakarta',
            'role'     => 'admin',
        ]);
    }
}
