<?php

namespace App\Http\Controllers;

use App\Models\ActivityReport;
use App\Models\ActivityReportFile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ActivityReportController extends Controller
{
    // ── Siswa ──────────────────────────────────────────────────────────────────

    /** Daftar laporan milik siswa yang login */
    public function index(Request $request): View
    {
        $reports = ActivityReport::with('files')
            ->where('user_id', $request->user()->id)
            ->latest('tanggal')
            ->paginate(10);

        return view('activity-reports.index', compact('reports'));
    }

    /** Form buat laporan baru */
    public function create(): View
    {
        return view('activity-reports.create');
    }

    /** Simpan laporan + upload file */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tanggal'     => ['required', 'date', 'before_or_equal:today'],
            'judul'       => ['required', 'string', 'max:255'],
            'deskripsi'   => ['nullable', 'string', 'max:2000'],
            'files'       => ['nullable', 'array', 'max:10'],
            'files.*'     => [
                'file',
                'max:51200', // 50 MB per file
                'mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,webm,pdf,doc,docx,xls,xlsx,ppt,pptx',
            ],
        ]);

        $report = ActivityReport::create([
            'user_id'    => $request->user()->id,
            'tanggal'    => $request->tanggal,
            'judul'      => $request->judul,
            'deskripsi'  => $request->deskripsi,
        ]);

        foreach ($request->file('files', []) as $file) {
            $path = $file->store('activity-reports/' . $request->user()->id, 'public');

            ActivityReportFile::create([
                'activity_report_id' => $report->id,
                'original_name'      => $file->getClientOriginalName(),
                'path'               => $path,
                'mime_type'          => $file->getMimeType(),
                'size'               => $file->getSize(),
            ]);
        }

        return redirect()->route('activity-reports.index')
            ->with('success', 'Laporan kegiatan berhasil dikirim.');
    }

    /** Detail laporan — siswa lihat milik sendiri, guru lihat semua */
    public function show(Request $request, ActivityReport $activityReport): View
    {
        $user = $request->user();

        // Siswa hanya boleh lihat miliknya sendiri
        if ($user->isSiswa() && $activityReport->user_id !== $user->id) {
            abort(403);
        }

        $activityReport->load('files', 'user');

        return view('activity-reports.show', compact('activityReport'));
    }

    /** Hapus laporan (siswa, hanya milik sendiri) */
    public function destroy(Request $request, ActivityReport $activityReport): RedirectResponse
    {
        if ($activityReport->user_id !== $request->user()->id) {
            abort(403);
        }

        // Hapus semua file dari storage
        foreach ($activityReport->files as $file) {
            Storage::disk('public')->delete($file->path);
        }

        $activityReport->delete();

        return back()->with('success', 'Laporan berhasil dihapus.');
    }

    // ── Guru ───────────────────────────────────────────────────────────────────

    /** Guru: daftar laporan semua siswa, digroup per siswa */
    public function guruIndex(Request $request): View
    {
        $siswaId = $request->get('siswa');
        $date    = $request->get('date');

        // Daftar siswa aktif untuk dropdown filter
        $siswaList = User::where('role', 'siswa')
            ->orderBy('name')
            ->get(['id', 'name', 'jurusan', 'avatar']);

        $reports = ActivityReport::with(['user', 'files'])
            ->whereHas('user', fn ($q) => $q->where('role', 'siswa'))
            ->when($siswaId, fn ($q) => $q->where('user_id', $siswaId))
            ->when($date,    fn ($q) => $q->whereDate('tanggal', $date))
            ->latest('tanggal')
            ->paginate(15);

        return view('activity-reports.guru-index', compact('reports', 'siswaList', 'siswaId', 'date'));
    }
}
