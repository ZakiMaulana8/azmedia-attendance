<?php

return [
    'nama_lokasi' => env('GEOFENCE_NAMA_LOKASI', 'AZ Media'),
    'latitude'    => (float) env('GEOFENCE_LATITUDE', -8.031133759026867),
    'longitude'   => (float) env('GEOFENCE_LONGITUDE', 111.90199188567162),
    'radius'      => (int) env('GEOFENCE_RADIUS', 100),
];
