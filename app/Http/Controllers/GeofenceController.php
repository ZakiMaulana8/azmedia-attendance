<?php

namespace App\Http\Controllers;

use App\Models\Geofence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeofenceController extends Controller
{
    public function index(): View
    {
        $geofence = Geofence::active() ?? new Geofence([
            'nama_lokasi' => '',
            'latitude'    => -6.2088,
            'longitude'   => 106.8456,
            'radius'      => 100,
        ]);

        return view('admin.geofence', compact('geofence'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_lokasi' => ['required', 'string', 'max:255'],
            'latitude'    => ['required', 'numeric', 'between:-90,90'],
            'longitude'   => ['required', 'numeric', 'between:-180,180'],
            'radius'      => ['required', 'integer', 'min:50', 'max:5000'],
        ]);

        // Nonaktifkan geofence lama
        Geofence::query()->update(['is_active' => false]);

        Geofence::create([
            'nama_lokasi' => $request->nama_lokasi,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'radius'      => $request->radius,
            'is_active'   => true,
        ]);

        return back()->with('success', 'Pengaturan geofence berhasil disimpan.');
    }
}
