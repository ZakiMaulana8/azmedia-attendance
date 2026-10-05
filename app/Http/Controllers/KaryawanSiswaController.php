<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KaryawanSiswaController extends Controller
{
    /**
     * Tampilkan daftar pemantauan siswa PKL aktif beserta status absensi hari ini.
     * Hanya read-only untuk role karyawan (pembimbing lapangan).
     */
    public function index(Request $request): View
    {
        // Siswa magang aktif (belum melewati tanggal selesai atau belum diset)
        $todaySiswa = User::where('role', 'siswa')
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', today());
            })
            ->with(['attendances' => function ($q) {
                $q->whereDate('tanggal', today());
            }])
            ->orderBy('name')
            ->get();

        $sudahAbsen = 0;
        $belumAbsen = 0;
        $izin       = 0;
        $alpa       = 0;

        foreach ($todaySiswa as $siswa) {
            $att = $siswa->attendances->first();

            if (! $att) {
                $belumAbsen++;
            } elseif (in_array($att->status, ['hadir', 'terlambat'])) {
                $sudahAbsen++;
            } elseif ($att->status === 'izin') {
                $izin++;
            } elseif ($att->status === 'alpa') {
                $alpa++;
            } else {
                $belumAbsen++;
            }
        }

        $todayStats = [
            'total'       => $todaySiswa->count(),
            'sudah_absen' => $sudahAbsen,
            'belum_absen' => $belumAbsen,
            'izin'        => $izin,
            'alpa'        => $alpa,
        ];

        return view('karyawan.siswa.index', compact('todaySiswa', 'todayStats'));
    }
}
