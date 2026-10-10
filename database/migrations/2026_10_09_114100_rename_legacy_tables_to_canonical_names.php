<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Migration kompatibilitas: menyinkronkan nama tabel lama (singular/non-standar)
 * yang dibuat oleh migration awal dengan nama tabel canonical yang digunakan
 * oleh model Eloquent dan database azmedia_db (plural).
 *
 * Tabel yang diperhatikan:
 *   geofence  → geofences   (app/Models/Geofence.php: $table = 'geofences')
 *   absensi   → attendances  (app/Models/Attendance.php: $table = 'attendances')
 *
 * Perlindungan: setiap rename hanya dieksekusi jika:
 *   - Tabel lama ADA, DAN
 *   - Tabel canonical BELUM ADA
 * Jika keduanya ada sekaligus, rename dilewati dan kondisi tersebut dicatat sebagai
 * ambigu agar operator dapat menanganinya secara manual.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. geofence → geofences ─────────────────────────────────────────
        $hasOld      = Schema::hasTable('geofence');
        $hasCanonical = Schema::hasTable('geofences');

        if ($hasOld && $hasCanonical) {
            // Kondisi ambigu: kedua tabel ada, jangan ubah keduanya.
            // Operator harus menangani ini secara manual.
        } elseif ($hasOld && ! $hasCanonical) {
            Schema::rename('geofence', 'geofences');
        }
        // Jika hanya canonical yang ada (atau keduanya tidak ada), tidak ada yang perlu dilakukan.

        // ── 2. absensi → attendances ─────────────────────────────────────────
        $hasOldAttendance      = Schema::hasTable('absensi');
        $hasCanonicalAttendance = Schema::hasTable('attendances');

        if ($hasOldAttendance && $hasCanonicalAttendance) {
            // Kondisi ambigu: kedua tabel ada, jangan ubah keduanya.
        } elseif ($hasOldAttendance && ! $hasCanonicalAttendance) {
            Schema::rename('absensi', 'attendances');
        }
    }

    /**
     * Strategi rollback (down):
     *
     * Rollback penuh (rename balik ke nama lama) TIDAK dilakukan karena:
     *   1. Database azmedia_db sudah menggunakan nama canonical sebelum migration ini ada.
     *   2. Rollback ke 'geofence' dan 'absensi' akan merusak model Eloquent dan
     *      seluruh fitur aplikasi yang mengandalkan nama tabel canonical.
     *   3. Jika migration ini sudah berjalan di production, rollback ke nama lama
     *      akan menghasilkan downtime.
     *
     * Jika rollback terpaksa diperlukan secara manual (misalnya di lingkungan dev),
     * gunakan Tinker atau query langsung:
     *   Schema::rename('geofences', 'geofence');
     *   Schema::rename('attendances', 'absensi');
     * sambil pastikan tidak ada request aktif ke database pada saat itu.
     *
     * down() sengaja dibiarkan no-op untuk menghindari kerusakan data secara otomatis.
     */
    public function down(): void
    {
        // Sengaja no-op. Lihat komentar di atas untuk strategi rollback manual.
    }
};
