<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Dummy
        User::create([
            'name' => 'Admin Utama',
            'identifier_number' => '111111',
            'email' => 'admin@kampus.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Pak Jono (Laboran)',
            'identifier_number' => '222222',
            'email' => 'laboran@kampus.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'laboran',
        ]);

        User::create([
            'name' => 'Fathin Rafansyah',
            'identifier_number' => '2403421036',
            'email' => 'fathin@student.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
        ]);

        // 2. Data Fasilitas Dummy
        Facility::create([
            'name' => 'Laboratorium Komputer & Jaringan',
            'category' => 'ruangan',
            'description' => 'Lab di lantai 2 dengan kapasitas 30 PC dan akses router Cisco.',
        ]);

        Facility::create([
            'name' => 'ESP32 & IoT Development Kit - Pack A',
            'category' => 'kit_iot',
            'description' => 'Satu paket berisi ESP32, sensor HC-SR04, LCD I2C, dan kabel jumper.',
        ]);

        Facility::create([
            'name' => 'Digital Oscilloscope Rig 1',
            'category' => 'alat_elektronik',
            'description' => 'Alat ukur sinyal elektronik frekuensi tinggi di Lab Telekomunikasi.',
        ]);
    }
}