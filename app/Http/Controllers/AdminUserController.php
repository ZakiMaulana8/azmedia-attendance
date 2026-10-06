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
        $query = User::where('role', 'siswa')->withCount('attendances');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nis_nip', 'like', "%{$search}%")
                  ->orWhere('asal_sekolah', 'like', "%{$search}%");
            });
        }

        $siswas = $query->orderBy('name')->paginate(10)->withQueryString();

        // Stats (always from full dataset, not just current page)
        $allSiswa       = User::where('role', 'siswa');
        $totalSiswa     = $allSiswa->count();
        $activeSiswa    = User::where('role', 'siswa')->whereNotNull('tanggal_selesai')->where('tanggal_selesai', '>=', today())->count();
        $schoolCount    = User::where('role', 'siswa')->whereNotNull('asal_sekolah')->distinct('asal_sekolah')->count('asal_sekolah');
        $totalAbsensi   = \App\Models\Attendance::whereHas('user', fn($q) => $q->where('role', 'siswa'))->count();

        return view('admin.siswa.index', compact('siswas', 'totalSiswa', 'activeSiswa', 'schoolCount', 'totalAbsensi'));
    }

    /**
     * Tampilkan daftar Guru Pembimbing.
     */
    public function indexGuru(Request $request): View
    {
        $query = User::where('role', 'guru');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nis_nip', 'like', "%{$search}%")
                  ->orWhere('asal_sekolah', 'like', "%{$search}%");
            });
        }

        $gurus = $query->orderBy('name')->paginate(10)->withQueryString();

        // Stats (always from full dataset)
        $totalGuru      = User::where('role', 'guru')->count();
        $withNip        = User::where('role', 'guru')->whereNotNull('nis_nip')->where('nis_nip', '!=', '')->count();
        $instansiCount  = User::where('role', 'guru')->whereNotNull('asal_sekolah')->distinct('asal_sekolah')->count('asal_sekolah');
        $withPhone      = User::where('role', 'guru')->whereNotNull('phone')->where('phone', '!=', '')->count();

        return view('admin.guru.index', compact('gurus', 'totalGuru', 'withNip', 'instansiCount', 'withPhone'));
    }

    /**
     * Tampilkan daftar Karyawan.
     */
    public function indexKaryawan(Request $request): View
    {
        $query = User::where('role', 'karyawan');

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
        $totalKaryawan = User::where('role', 'karyawan')->count();
        $withPhone     = User::where('role', 'karyawan')->whereNotNull('phone')->where('phone', '!=', '')->count();
        $withGoogle    = User::where('role', 'karyawan')->whereNotNull('google_id')->count();
        $withAvatar    = User::where('role', 'karyawan')->whereNotNull('avatar')->where('avatar', '!=', '')->count();

        return view('admin.karyawan.index', compact('karyawans', 'totalKaryawan', 'withPhone', 'withGoogle', 'withAvatar'));
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
     * Hapus Pengguna.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
