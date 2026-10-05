<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PermissionController extends Controller
{
    // ── Siswa ──────────────────────────────────────────────────────────────────

    /** Riwayat izin milik siswa yang sedang login */
    public function index(Request $request): View
    {
        $permissions = $request->user()
            ->permissions()
            ->latest()
            ->paginate(10);

        return view('permissions.index', compact('permissions'));
    }

    public function create(): View
    {
        return view('permissions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'jenis'    => ['required', 'in:sakit,izin,dispensasi'],
            'tanggal'  => ['required', 'date'],
            'alasan'   => ['required', 'string', 'min:3'],
            'lampiran' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $path = null;
        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('permissions', 'public');
        }

        Permission::create([
            'user_id'  => $request->user()->id,
            'jenis'    => $request->jenis,
            'tanggal'  => $request->tanggal,
            'alasan'   => $request->alasan,
            'lampiran' => $path,
            'status'   => 'menunggu',
        ]);

        return back()->with('success', 'Pengajuan izin berhasil dikirim. Menunggu persetujuan guru.');
    }

    // ── Guru ───────────────────────────────────────────────────────────────────

    /** Daftar semua izin siswa (untuk guru) */
    public function approvals(Request $request): View
    {
        $status = $request->get('status', 'menunggu');

        $permissions = Permission::with('user')
            ->when($status !== 'semua', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        return view('permissions.approvals', compact('permissions', 'status'));
    }

    /** Detail satu izin — guru bisa lihat + approve/tolak di sini */
    public function show(Permission $permission): View
    {
        $permission->load('user', 'approver');

        return view('permissions.show', compact('permission'));
    }

    /** Approve / tolak via AJAX (dari dashboard card atau halaman detail) */
    public function approve(Request $request, Permission $permission): JsonResponse
    {
        $request->validate([
            'action'  => ['required', 'in:disetujui,ditolak'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $permission) {
            $permission->update([
                'status'           => $request->action,
                'approver_id'      => $request->user()->id,
                'catatan_approver' => $request->catatan,
                'approved_at'      => now(),
            ]);

            if ($request->action === 'disetujui') {
                $attendance = Attendance::where('user_id', $permission->user_id)
                    ->whereDate('tanggal', $permission->tanggal)
                    ->first();

                if (! $attendance) {
                    Attendance::create([
                        'user_id'    => $permission->user_id,
                        'tanggal'    => $permission->tanggal,
                        'status'     => 'izin',
                        'keterangan' => $permission->jenis_label ?: 'Izin',
                    ]);
                } elseif (! $attendance->jam_masuk && $attendance->status !== 'hadir') {
                    $attendance->update([
                        'status'     => 'izin',
                        'keterangan' => $attendance->keterangan ?: ($permission->jenis_label ?: 'Izin'),
                    ]);
                }
            }
        });

        return response()->json([
            'success'      => true,
            'status'       => $permission->status,
            'status_label' => $permission->status_label,
        ]);
    }
}
