<?php

namespace Database\Seeders;

use App\Models\Geofence;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────────
        User::create([
            'name'              => 'Admin Azmedia',
            'email'             => 'admin@azmedia.id',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        // ── Guru Pembimbing ────────────────────────────────────────
        User::create([
            'name'              => 'Budi Hartono',
            'email'             => 'guru@azmedia.id',
            'password'          => Hash::make('password'),
            'role'              => 'guru',
            'nis_nip'           => '197501012000031001',
            'phone'             => '081234567890',
            'email_verified_at' => now(),
        ]);

        // ── Siswa Magang – RPL ─────────────────────────────────────
        User::create([
            'name'              => 'Ahmad Fauzi',
            'email'             => 'ahmad.fauzi@siswa.sch.id',
            'password'          => Hash::make('password'),
            'role'              => 'siswa',
            'nis_nip'           => '2024001',
            'asal_sekolah'      => 'SMK Negeri 1 Jakarta',
            'jurusan'           => 'RPL',
            'tanggal_mulai'     => '2025-01-06',
            'tanggal_selesai'   => '2025-03-28',
            'phone'             => '089876543210',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'              => 'Rizky Pratama',
            'email'             => 'rizky.pratama@siswa.sch.id',
            'password'          => Hash::make('password'),
            'role'              => 'siswa',
            'nis_nip'           => '2024003',
            'asal_sekolah'      => 'SMK Negeri 1 Jakarta',
            'jurusan'           => 'RPL',
            'tanggal_mulai'     => '2025-01-06',
            'tanggal_selesai'   => '2025-03-28',
            'phone'             => '082233445566',
            'email_verified_at' => now(),
        ]);

        // ── Siswa Magang – Broadcast ───────────────────────────────
        User::create([
            'name'              => 'Siti Aminah',
            'email'             => 'siti.aminah@siswa.sch.id',
            'password'          => Hash::make('password'),
            'role'              => 'siswa',
            'nis_nip'           => '2024002',
            'asal_sekolah'      => 'SMK Negeri 1 Jakarta',
            'jurusan'           => 'Broadcast',
            'tanggal_mulai'     => '2025-01-06',
            'tanggal_selesai'   => '2025-03-28',
            'phone'             => '087654321098',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'              => 'Dewi Rahayu',
            'email'             => 'dewi.rahayu@siswa.sch.id',
            'password'          => Hash::make('password'),
            'role'              => 'siswa',
            'nis_nip'           => '2024004',
            'asal_sekolah'      => 'SMK Negeri 1 Jakarta',
            'jurusan'           => 'Broadcast',
            'tanggal_mulai'     => '2025-01-06',
            'tanggal_selesai'   => '2025-03-28',
            'phone'             => '085566778899',
            'email_verified_at' => now(),
        ]);

        // ── Geofence default ───────────────────────────────────────
        Geofence::create([
            'nama_lokasi' => config('geofence.nama_lokasi'),
            'latitude'    => config('geofence.latitude'),
            'longitude'   => config('geofence.longitude'),
            'radius'      => config('geofence.radius'),
            'is_active'   => true,
        ]);
    }
}
