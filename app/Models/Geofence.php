<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Geofence extends Model
{
    protected $fillable = [
        'nama_lokasi',
        'latitude',
        'longitude',
        'radius',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude'  => 'float',
            'longitude' => 'float',
            'radius'    => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public static function active(): ?self
    {
        return static::where('is_active', true)->latest()->first();
    }
}
