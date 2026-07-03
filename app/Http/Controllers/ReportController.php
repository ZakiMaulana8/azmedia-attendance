<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return $user->isGuru() || $user->isAdmin()
            ? $this->guruReport($request)
            : $this->siswaReport($request, $user);
    }

    // ── Laporan Siswa ──────────────────────────────────────────────────────────

    private function siswaReport(Request $request, User $user): View
    {
        // Default bulan ini
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year',  now()->year);

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        // Ambil semua record absensi bulan ini
        $attendances = $user->attendances()
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->get()
            ->keyBy(fn ($a) => $a->tanggal->format('Y-m-d'));

        // Summary counts
        $summary = [
            'hadir'     => $attendances->whereIn('status', ['hadir', 'terlambat'])->count(),
            'terlambat' => $attendances->where('status', 'terlambat')->count(),
            'izin'      => $attendances->where('status', 'izin')->count(),
            'alpa'      => 0, // dihitung dari hari kerja - absen
        ];

        // Hari kerja di bulan ini (Senin–Jumat)
        $workdays = collect();
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if ($d->isWeekday()) {
                $workdays->push($d->copy());
            }
        }
        $summary['alpa'] = max(0, $workdays->count() - $attendances->count());

        // Izin bulan ini
        $permissions = $user->permissions()
            ->whereBetween('tanggal', [$start, $end])
            ->latest()
            ->get();

        return view('reports.siswa', compact(
            'user', 'attendances', 'workdays',
            'summary', 'permissions', 'month', 'year'
        ));
    }

    // ── Laporan Guru ──────────────────────────────────────────────────────────

    private function guruReport(Request $request): View
    {
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year',  now()->year);

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        // Semua siswa magang aktif
        $siswaList = User::where('role', 'siswa')
            ->where(function ($q) use ($end) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', $end);
            })
            ->get();

        // Summary per siswa
        $rekapSiswa = $siswaList->map(function (User $siswa) use ($start, $end) {
            $atts = $siswa->attendances()
                ->whereBetween('tanggal', [$start, $end])
                ->get();

            return [
                'user'      => $siswa,
                'hadir'     => $atts->whereIn('status', ['hadir', 'terlambat'])->count(),
                'terlambat' => $atts->where('status', 'terlambat')->count(),
                'izin'      => $atts->where('status', 'izin')->count(),
                'alpa'      => max(0, $this->countWorkdays($start, $end) - $atts->count()),
            ];
        });

        // Rekap total keseluruhan
        $totalStats = [
            'hadir'     => $rekapSiswa->sum('hadir'),
            'terlambat' => $rekapSiswa->sum('terlambat'),
            'izin'      => $rekapSiswa->sum('izin'),
            'alpa'      => $rekapSiswa->sum('alpa'),
        ];

        // Izin pending di bulan ini
        $pendingIzin = Permission::with('user')
            ->where('status', 'menunggu')
            ->whereBetween('tanggal', [$start, $end])
            ->latest()
            ->get();

        return view('reports.guru', compact(
            'rekapSiswa', 'totalStats', 'pendingIzin', 'month', 'year'
        ));
    }

    private function countWorkdays(Carbon $start, Carbon $end): int
    {
        $count = 0;
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if ($d->isWeekday()) $count++;
        }
        return $count;
    }
}
