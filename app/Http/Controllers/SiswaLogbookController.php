<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiswaLogbookController extends Controller
{
    /**
     * Tampilkan riwayat absensi + logbook + foto seorang siswa.
     * Read-only, hanya untuk role guru pembimbing dan karyawan (pembimbing lapangan).
     */
    public function show(Request $request, User $siswa): View
    {
        $viewer = $request->user();

        abort_unless($viewer->isGuru() || $viewer->isKaryawan(), 403);
        abort_unless($siswa->isSiswa(), 404);

        $attendances = $siswa->attendances()
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('siswa.logbook', [
            'siswa'       => $siswa,
            'attendances' => $attendances,
            'viewerRole'  => $viewer->isGuru() ? 'guru' : 'karyawan',
        ]);
    }
}
