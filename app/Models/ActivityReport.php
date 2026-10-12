<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityReport extends Model
{
    protected $table = 'laporan_kegiatan';

    protected $fillable = [
        'user_id',
        'tanggal',
        'judul',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function files(): HasMany
    {
        return $this->hasMany(ActivityReportFile::class, 'laporan_kegiatan_id');
    }

    public function lampiran(): HasMany
    {
        return $this->files();
    }
}
