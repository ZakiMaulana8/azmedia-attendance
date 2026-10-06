<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Tampilkan daftar Siswa Magang.
     */
    public function indexSiswa(Request $request): View
    {
        $currentView = $request->query('view', 'active');
        if ($currentView === 'deleted') {
            $query = User::onlyTrashed()->where('role', 'siswa')->withCount('attendances');
        } else {
            $query = User::where('role', 'siswa')->withCount('attendances');
            $currentView = 'active';
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nis_nip', 'like', "%{$search}%")
                  ->orWhere('asal_sekolah', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $siswas = $query->orderBy('name')->paginate(10)->withQueryString();

        // Stats (always from full normal dataset, not just current page)
        $totalSiswa     = User::where('role', 'siswa')->count();
        $activeSiswa    = User::where('role', 'siswa')->where('is_active', true)->whereNotNull('tanggal_selesai')->where('tanggal_selesai', '>=', today())->count();
        $schoolCount    = User::where('role', 'siswa')->whereNotNull('asal_sekolah')->distinct('asal_sekolah')->count('asal_sekolah');
        $totalAbsensi   = \App\Models\Attendance::whereHas('user', fn($q) => $q->withoutTrashed()->where('role', 'siswa'))->count();
        $deletedSiswa   = User::onlyTrashed()->where('role', 'siswa')->count();

        return view('admin.siswa.index', compact(
            'siswas',
            'totalSiswa',
            'activeSiswa',
            'schoolCount',
            'totalAbsensi',
            'deletedSiswa',
            'currentView'
        ));
    }

    /**
     * Tampilkan daftar Guru Pembimbing.
     */
    public function indexGuru(Request $request): View
    {
        $currentView = $request->query('view', 'active');
        if ($currentView === 'deleted') {
            $query = User::onlyTrashed()->where('role', 'guru');
        } else {
            $query = User::where('role', 'guru');
            $currentView = 'active';
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nis_nip', 'like', "%{$search}%")
                  ->orWhere('asal_sekolah', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $gurus = $query->orderBy('name')->paginate(10)->withQueryString();

        // Stats (always from full normal dataset)
        $totalGuru      = User::where('role', 'guru')->count();
        $withNip        = User::where('role', 'guru')->whereNotNull('nis_nip')->where('nis_nip', '!=', '')->count();
        $instansiCount  = User::where('role', 'guru')->whereNotNull('asal_sekolah')->distinct('asal_sekolah')->count('asal_sekolah');
        $withPhone      = User::where('role', 'guru')->whereNotNull('phone')->where('phone', '!=', '')->count();
        $deletedGuru    = User::onlyTrashed()->where('role', 'guru')->count();

        return view('admin.guru.index', compact(
            'gurus',
            'totalGuru',
            'withNip',
            'instansiCount',
            'withPhone',
            'deletedGuru',
            'currentView'
        ));
    }

    /**
     * Tampilkan daftar Karyawan.
     */
    public function indexKaryawan(Request $request): View
    {
        $currentView = $request->query('view', 'active');
        if ($currentView === 'deleted') {
            $query = User::onlyTrashed()->where('role', 'karyawan');
        } else {
            $query = User::where('role', 'karyawan');
            $currentView = 'active';
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $karyawans = $query->orderBy('name')->paginate(10)->withQueryString();

        // Stats (always from full dataset)
        $totalKaryawan   = User::where('role', 'karyawan')->count();
        $activeKaryawan  = User::where('role', 'karyawan')->where('is_active', true)->count();
        $withPhone       = User::where('role', 'karyawan')->whereNotNull('phone')->where('phone', '!=', '')->count();
        $withGoogle      = User::where('role', 'karyawan')->whereNotNull('google_id')->count();
        $withAvatar      = User::where('role', 'karyawan')->whereNotNull('avatar')->where('avatar', '!=', '')->count();
        $deletedKaryawan = User::onlyTrashed()->where('role', 'karyawan')->count();

        return view('admin.karyawan.index', compact(
            'karyawans',
            'totalKaryawan',
            'activeKaryawan',
            'withPhone',
            'withGoogle',
            'withAvatar',
            'deletedKaryawan',
            'currentView'
        ));
    }


    /**
     * Update data Pengguna.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'            => ['required', 'string', 'in:siswa,guru,admin,karyawan'],
            'nis_nip'         => ['nullable', 'string', 'max:50'],
            'asal_sekolah'    => ['nullable', 'string', 'max:255'],
            'jurusan'         => ['nullable', 'string', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:20'],
            'tanggal_mulai'   => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'password'        => ['nullable', 'string', 'min:6'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Soft Delete Pengguna.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat melakukan Soft Delete pada akun Anda sendiri.');
        }

        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Akun dengan role Admin tidak dapat di-Soft Delete.');
        }

        $user->delete();

        return redirect()->back()->with('success', "Akun {$user->name} berhasil di-Soft Delete.");
    }

    /**
     * Restore akun pengguna yang Soft Deleted.
     */
    public function restore(User $user): RedirectResponse
    {
        if (! $user->trashed()) {
            return redirect()->back()->with('info', "Akun {$user->name} tidak dalam status Soft Deleted.");
        }

        $user->restore();

        return redirect()->back()->with('success', "Akun {$user->name} berhasil di-Restore.");
    }

    /**
     * Aktifkan akun pengguna.
     */
    public function activate(User $user): RedirectResponse
    {
        $user->update(['is_active' => true]);

        return redirect()->back()->with('success', "Akun {$user->name} berhasil diaktifkan.");
    }

    /**
     * Nonaktifkan akun pengguna.
     */
    public function deactivate(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Akun dengan role Admin tidak dapat dinonaktifkan.');
        }

        $user->update(['is_active' => false]);

        return redirect()->back()->with('success', "Akun {$user->name} berhasil dinonaktifkan.");
    }
}
