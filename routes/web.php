<?php

use App\Http\Controllers\ActivityReportController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeofenceController;
use App\Http\Controllers\KaryawanSiswaController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Dev login bypass (only in local environment)
if (app()->environment('local')) {
    Route::get('/dev/login/{role}', function ($role) {
        $user = \App\Models\User::where('role', $role)->first();
        if ($user) {
            \Illuminate\Support\Facades\Auth::login($user);
            return redirect()->route('dashboard');
        }
        return 'User with role ' . $role . ' not found.';
    })->name('dev.login');
}

// ── Authenticated Routes ───────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard (role-aware)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Absensi reports (rekap statistik)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Laporan kegiatan / pertanggungjawaban
    Route::get('/activity-reports/guru', [ActivityReportController::class, 'guruIndex'])
        ->name('activity-reports.guru');
    Route::get('/activity-reports/create', [ActivityReportController::class, 'create'])
        ->name('activity-reports.create');
    Route::get('/activity-reports/{activityReport}', [ActivityReportController::class, 'show'])
        ->name('activity-reports.show');
    Route::delete('/activity-reports/{activityReport}', [ActivityReportController::class, 'destroy'])
        ->name('activity-reports.destroy');
    Route::get('/activity-reports', [ActivityReportController::class, 'index'])
        ->name('activity-reports.index');
    Route::post('/activity-reports', [ActivityReportController::class, 'store'])
        ->name('activity-reports.store');

    // Attendance
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

    // Permissions (izin) — siswa
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');

    // Permissions — approval (guru & karyawan)
    Route::get('/permissions/approvals', [PermissionController::class, 'approvals'])
        ->name('permissions.approvals')
        ->middleware('can:viewAny,App\Models\Permission');
    Route::patch('/permissions/{permission}/approve', [PermissionController::class, 'approve'])
        ->name('permissions.approve')
        ->middleware('can:update,permission');

    // Karyawan area — pemantauan siswa PKL (middleware: auth + karyawan)
    Route::middleware('karyawan')->group(function () {
        Route::get('/karyawan/siswa', [KaryawanSiswaController::class, 'index'])->name('karyawan.siswa.index');
    });

    // Admin area — hanya role `admin` (middleware: auth + admin)
    Route::middleware('admin')->group(function () {
        // Admin: Geofence settings
        Route::get('/admin/geofence', [GeofenceController::class, 'index'])->name('admin.geofence');
        Route::post('/admin/geofence', [GeofenceController::class, 'store'])->name('admin.geofence.store');

        // Admin: User Management (Siswa & Guru)
        Route::get('/admin/siswa', [AdminUserController::class, 'indexSiswa'])->name('admin.siswa.index');
        Route::get('/admin/guru', [AdminUserController::class, 'indexGuru'])->name('admin.guru.index');
        Route::get('/admin/karyawan', [AdminUserController::class, 'indexKaryawan'])->name('admin.karyawan.index');
        Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });
});

require __DIR__ . '/auth.php';
