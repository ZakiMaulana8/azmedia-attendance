<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'user_id',
        'approver_id',
        'jenis',
        'tanggal',
        'alasan',
        'lampiran',
        'status',
        'catatan_approver',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'     => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function getJenisLabelAttribute(): string
    {
        return match($this->jenis) {
            'sakit'       => 'Izin Sakit',
            'izin'        => 'Izin',
            'dispensasi'  => 'Dispensasi',
            default       => $this->jenis,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu'   => 'Menunggu Persetujuan',
            'disetujui'  => 'Disetujui',
            'ditolak'    => 'Ditolak',
            default      => $this->status,
        };
    }
}
