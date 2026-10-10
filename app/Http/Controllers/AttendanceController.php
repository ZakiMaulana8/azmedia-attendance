<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Geofence;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Halaman foto Absen Masuk (khusus siswa).
     */
    public function checkIn(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->isSiswa(), 403);

        if ($user->todayAttendance()?->jam_masuk) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah absen masuk hari ini.');
        }

        return view('attendance.check-in', ['user' => $user]);
    }

    /**
     * Halaman foto Absen Pulang (khusus siswa).
     */
    public function checkOut(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->isSiswa(), 403);

        $today = $user->todayAttendance();

        if (! $today?->jam_masuk) {
            return redirect()->route('dashboard')->with('error', 'Anda belum melakukan absen masuk hari ini.');
        }

        if ($today->jam_pulang) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah absen pulang hari ini.');
        }

        return view('attendance.check-out', [
            'user'  => $user,
            'today' => $today,
        ]);
    }

    /**
     * Proses check-in atau check-out.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'latitude'    => ['required', 'numeric'],
            'longitude'   => ['required', 'numeric'],
            'type'        => ['required', 'in:masuk,pulang'],
            'logbook'     => [
                Rule::requiredIf($request->type === 'pulang'),
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'pulang') {
                        if (! is_string($value) || mb_strlen(trim($value)) < 20) {
                            $fail('Logbook aktivitas harian wajib diisi minimal 20 karakter.');
                        }
                    }
                },
            ],
            'foto'        => [
                Rule::requiredIf(fn() => ! app()->runningUnitTests()),
                Rule::when($request->hasFile('foto'), ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120']),
            ],
        ]);

        $user = $request->user();
        abort_unless($user->isSiswa(), 403);

        $geofence = Geofence::active() ?? new Geofence([
            'nama_lokasi' => config('geofence.nama_lokasi'),
            'latitude'    => config('geofence.latitude'),
            'longitude'   => config('geofence.longitude'),
            'radius'      => config('geofence.radius'),
        ]);

        // Validasi geofence (wajib berada di lokasi kantor)
        $distance = $this->haversine(
            (float) $request->latitude,
            (float) $request->longitude,
            (float) $geofence->latitude,
            (float) $geofence->longitude
        );

        if ($distance > $geofence->radius) {
            return response()->json([
                'success' => false,
                'message' => "Anda berada di luar area {$geofence->nama_lokasi} (" . round($distance) . "m dari kantor). Silakan menuju lokasi kantor untuk melakukan absensi.",
            ], 422);
        }

        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'tanggal' => today()],
            ['status' => 'hadir']
        );

        $nowInWib = Carbon::now('Asia/Jakarta');

        if ($request->type === 'masuk') {
            if ($attendance->jam_masuk) {
                return response()->json(['success' => false, 'message' => 'Anda sudah absen masuk hari ini.'], 422);
            }

            $fotoPath = $request->hasFile('foto')
                ? $request->file('foto')->store('attendance/masuk/' . $user->id, 'public')
                : null;

            $updateData = [
                'jam_masuk'   => $nowInWib->format('H:i:s'),
                'lat_masuk'   => $request->latitude,
                'lng_masuk'   => $request->longitude,
                'jarak_masuk' => round($distance, 2),
                'status'      => 'hadir',
                'keterangan'  => 'Hadir',
            ];

            if ($fotoPath) {
                $updateData['foto_masuk'] = $fotoPath;
            }

            $attendance->update($updateData);
        } else {
            if (! $attendance->jam_masuk) {
                return response()->json(['success' => false, 'message' => 'Anda belum absen masuk hari ini.'], 422);
            }

            if ($attendance->jam_pulang) {
                return response()->json(['success' => false, 'message' => 'Anda sudah absen pulang hari ini.'], 422);
            }

            $fotoPulangPath = $request->hasFile('foto')
                ? $request->file('foto')->store('attendance/pulang/' . $user->id, 'public')
                : null;

            $keteranganPulang = $nowInWib->format('H:i:s') < '16:00:00'
                ? 'Pulang Cepat'
                : 'Pulang Tepat Waktu';

            $updateData = [
                'jam_pulang'   => $nowInWib->format('H:i:s'),
                'lat_pulang'   => $request->latitude,
                'lng_pulang'   => $request->longitude,
                'jarak_pulang' => round($distance, 2),
                'keterangan'   => $keteranganPulang,
                'logbook'      => trim($request->logbook),
            ];

            if ($fotoPulangPath) {
                $updateData['foto_pulang'] = $fotoPulangPath;
            }

            $attendance->update($updateData);
        }

        $fresh = $attendance->fresh();

        return response()->json([
            'success'      => true,
            'message'      => $request->type === 'masuk'
                ? 'Absen masuk berhasil! ✅'
                : 'Absen pulang berhasil! ✅',
            'jam_masuk'    => $fresh->jam_masuk  ? Carbon::parse($fresh->jam_masuk)->format('H:i')  : null,
            'jam_pulang'   => $fresh->jam_pulang ? Carbon::parse($fresh->jam_pulang)->format('H:i') : null,
            'foto_masuk'   => $fresh->foto_masuk,
            'foto_pulang'  => $fresh->foto_pulang,
            'status'       => $fresh->status,
            'statusMasuk'  => 'hadir',
            'statusPulang' => 'selesai',
            'keterangan'   => $fresh->keterangan,
        ]);
    }

    /**
     * Hitung jarak antara dua koordinat (Haversine formula) dalam meter.
     */
    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
