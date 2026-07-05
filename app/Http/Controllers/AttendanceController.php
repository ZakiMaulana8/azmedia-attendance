<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Geofence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Proses check-in atau check-out.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'latitude'  => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'type'      => ['required', 'in:masuk,pulang'],
        ]);

        $user     = $request->user();
        $geofence = Geofence::active() ?? new Geofence([
            'nama_lokasi' => config('geofence.nama_lokasi'),
            'latitude'    => config('geofence.latitude'),
            'longitude'   => config('geofence.longitude'),
            'radius'      => config('geofence.radius'),
        ]);

        // Validasi geofence
        if ($geofence) {
            $distance = $this->haversine(
                $request->latitude, $request->longitude,
                $geofence->latitude, $geofence->longitude
            );

            if ($distance > $geofence->radius) {
                return response()->json([
                    'success' => false,
                    'message' => "Anda berada di luar area {$geofence->nama_lokasi}. Silakan lakukan absensi di lokasi kantor.",
                ], 422);
            }
        }

        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'tanggal' => today()],
            ['status'  => 'hadir']
        );

        if ($request->type === 'masuk') {
            if ($attendance->jam_masuk) {
                return response()->json(['success' => false, 'message' => 'Anda sudah absen masuk hari ini.'], 422);
            }
            $attendance->update([
                'jam_masuk' => now()->format('H:i:s'),
                'lat_masuk' => $request->latitude,
                'lng_masuk' => $request->longitude,
                'status'    => now()->format('H:i') > '08:00' ? 'terlambat' : 'hadir',
            ]);
        } else {
            if (! $attendance->jam_masuk) {
                return response()->json(['success' => false, 'message' => 'Anda belum absen masuk hari ini.'], 422);
            }

            // Pengecekan jam kerja berakhir (16:00 WIB)
            $nowInWib = now('Asia/Jakarta');
            $statusPulang = $nowInWib->hour < 16 ? 'Pulang Cepat' : 'Pulang Tepat Waktu';

            $attendance->update([
                'jam_pulang' => $nowInWib->format('H:i:s'),
                'lat_pulang' => $request->latitude,
                'lng_pulang' => $request->longitude,
                'keterangan' => $statusPulang,
            ]);
        }

        return response()->json([
            'success'    => true,
            'message'    => $request->type === 'masuk' ? 'Absen masuk berhasil!' : 'Absen pulang berhasil!',
            'jam_masuk'  => $attendance->fresh()->jam_masuk
                                ? \Carbon\Carbon::parse($attendance->fresh()->jam_masuk)->format('H:i')
                                : null,
            'jam_pulang' => $attendance->fresh()->jam_pulang
                                ? \Carbon\Carbon::parse($attendance->fresh()->jam_pulang)->format('H:i')
                                : null,
            'status'     => $attendance->fresh()->status,
            'keterangan' => $attendance->fresh()->keterangan,
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
