<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Geofence;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
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
                $request->type === 'pulang' ? 'string' : 'nullable',
            ],
        ]);

        $user = $request->user();

        $geofence = Geofence::active() ?? new Geofence([
            'nama_lokasi' => config('geofence.nama_lokasi'),
            'latitude'    => config('geofence.latitude'),
            'longitude'   => config('geofence.longitude'),
            'radius'      => config('geofence.radius'),
        ]);

        // Validasi geofence (wajib berada di lokasi kantor)
        $distance = $this->haversine(
            (float) $request->latitude, (float) $request->longitude,
            (float) $geofence->latitude, (float) $geofence->longitude
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

            $attendance->update([
                'jam_masuk'   => $nowInWib->format('H:i:s'),
                'lat_masuk'   => $request->latitude,
                'lng_masuk'   => $request->longitude,
                'jarak_masuk' => round($distance, 2),
                'status'      => 'hadir',
                'keterangan'  => 'Hadir',
            ]);
        } else {
            if (! $attendance->jam_masuk) {
                return response()->json(['success' => false, 'message' => 'Anda belum absen masuk hari ini.'], 422);
            }

            $attendance->update([
                'jam_pulang'   => $nowInWib->format('H:i:s'),
                'lat_pulang'   => $request->latitude,
                'lng_pulang'   => $request->longitude,
                'jarak_pulang' => round($distance, 2),
                'keterangan'   => 'Hadir',
                'logbook'      => $request->logbook,
            ]);
        }

        $fresh = $attendance->fresh();

        return response()->json([
            'success'      => true,
            'message'      => $request->type === 'masuk'
                ? 'Absen masuk berhasil! ✅'
                : 'Absen pulang berhasil! ✅',
            'jam_masuk'    => $fresh->jam_masuk  ? Carbon::parse($fresh->jam_masuk)->format('H:i')  : null,
            'jam_pulang'   => $fresh->jam_pulang ? Carbon::parse($fresh->jam_pulang)->format('H:i') : null,
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
