<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return match (true) {
            $user->isAdmin() => $this->adminDashboard(),
            $user->isGuru()  => $this->guruDashboard($user),
            default          => $this->siswaDashboard($user),
        };
    }

    private function siswaDashboard(User $user): View
    {
        $today = $user->todayAttendance();

        return view('dashboard.siswa', compact('user', 'today'));
    }

    private function guruDashboard(User $user): View
    {
        // Semua siswa magang yang aktif (belum melewati tanggal selesai atau belum diset)
        $todaySiswa = User::where('role', 'siswa')
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', today());
            })
            ->get();

        // Pengajuan izin yang menunggu persetujuan
        $pendingPermissions = Permission::with('user')
            ->where('status', 'menunggu')
            ->latest()
            ->take(10)
            ->get();

        // Statistik absensi hari ini
        $todayStats = [
            'hadir' => Attendance::whereDate('tanggal', today())
                ->whereIn('status', ['hadir', 'terlambat'])
                ->count(),
            'izin'  => Attendance::whereDate('tanggal', today())
                ->where('status', 'izin')
                ->count(),
            'alpa'  => $todaySiswa->count()
                - Attendance::whereDate('tanggal', today())
                ->whereIn('user_id', $todaySiswa->pluck('id'))
                ->count(),
        ];

        return view('dashboard.guru', compact('user', 'todaySiswa', 'pendingPermissions', 'todayStats'));
    }

    private function adminDashboard(): View
    {
        return view('dashboard.admin');
    }
}
