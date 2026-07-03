<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ActivityReportFile extends Model
{
    protected $fillable = [
        'activity_report_id',
        'original_name',
        'path',
        'mime_type',
        'size',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(ActivityReport::class, 'activity_report_id');
    }

    /** URL publik file */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    /** Kategori tipe file untuk ikon dan preview */
    public function getTypeAttribute(): string
    {
        return match(true) {
            str_starts_with($this->mime_type, 'image/') => 'image',
            str_starts_with($this->mime_type, 'video/') => 'video',
            default                                      => 'document',
        };
    }

    /** Ukuran file yang sudah diformat */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes < 1024)       return $bytes . ' B';
        if ($bytes < 1048576)    return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }
}
