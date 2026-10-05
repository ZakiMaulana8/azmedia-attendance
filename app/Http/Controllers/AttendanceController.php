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
            'latitude'    => ['required_if:tipe_absen,wfo', 'nullable', 'numeric'],
            'longitude'   => ['required_if:tipe_absen,wfo', 'nullable', 'numeric'],
            'type'        => ['required', 'in:masuk,pulang'],
            'tipe_absen'  => ['required', 'in:wfo,wfh'],
            'logbook'     => [
                Rule::requiredIf($request->type === 'pulang'),
                $request->type === 'pulang' ? 'string' : 'nullable',
            ],
        ]);

        $user       = $request->user();
        $tipeAbsen  = $request->tipe_absen;
        $isWfh      = $tipeAbsen === 'wfh';

        $geofence = Geofence::active() ?? new Geofence([
            'nama_lokasi' => config('geofence.nama_lokasi'),
            'latitude'    => config('geofence.latitude'),
            'longitude'   => config('geofence.longitude'),
            'radius'      => config('geofence.radius'),
        ]);

        // Validasi geofence hanya untuk WFO
        $distance = null;
        if (!$isWfh && $geofence) {
            $distance = $this->haversine(
                (float) $request->latitude, (float) $request->longitude,
                (float) $geofence->latitude, (float) $geofence->longitude
            );

            if ($distance > $geofence->radius) {
                return response()->json([
                    'success' => false,
                    'message' => "Anda berada di luar area {$geofence->nama_lokasi}. Silakan lakukan absensi di lokasi kantor atau pilih mode WFH.",
                ], 422);
            }
        }

        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'tanggal' => today()],
            [
                'status'     => 'hadir',
                'tipe_absen' => $tipeAbsen,
            ]
        );

        $nowInWib = Carbon::now('Asia/Jakarta');
        $keterangan = $isWfh ? 'WFH' : 'Hadir';

        if ($request->type === 'masuk') {
            if ($attendance->jam_masuk) {
                return response()->json(['success' => false, 'message' => 'Anda sudah absen masuk hari ini.'], 422);
            }

            $attendance->update([
                'jam_masuk'   => $nowInWib->format('H:i:s'),
                'lat_masuk'   => $isWfh ? null : $request->latitude,
                'lng_masuk'   => $isWfh ? null : $request->longitude,
                'jarak_masuk' => $distance !== null ? round($distance, 2) : null,
                'tipe_absen'  => $tipeAbsen,
                'status'      => 'hadir',
                'keterangan'  => $keterangan,
            ]);
        } else {
            if (! $attendance->jam_masuk) {
                return response()->json(['success' => false, 'message' => 'Anda belum absen masuk hari ini.'], 422);
            }

            $attendance->update([
                'jam_pulang'   => $nowInWib->format('H:i:s'),
                'lat_pulang'   => $isWfh ? null : $request->latitude,
                'lng_pulang'   => $isWfh ? null : $request->longitude,
                'jarak_pulang' => $distance !== null ? round($distance, 2) : null,
                'keterangan'   => $keterangan,
                'logbook'      => $request->logbook,
            ]);
        }

        $fresh = $attendance->fresh();

        return response()->json([
            'success'      => true,
            'message'      => $request->type === 'masuk'
                ? ($isWfh ? 'Absen masuk WFH berhasil! 🏠' : 'Absen masuk berhasil! ✅')
                : ($isWfh ? 'Absen pulang WFH berhasil! 🏠' : 'Absen pulang berhasil! ✅'),
            'jam_masuk'    => $fresh->jam_masuk  ? Carbon::parse($fresh->jam_masuk)->format('H:i')  : null,
            'jam_pulang'   => $fresh->jam_pulang ? Carbon::parse($fresh->jam_pulang)->format('H:i') : null,
            'status'       => $fresh->status,
            'tipe_absen'   => $fresh->tipe_absen,
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
