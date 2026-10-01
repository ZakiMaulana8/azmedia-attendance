<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'google_id',
        'avatar',
        'password',
        'role',
        'nis_nip',
        'asal_sekolah',
        'jurusan',
        'tanggal_mulai',
        'tanggal_selesai',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'tanggal_mulai'     => 'date',
            'tanggal_selesai'   => 'date',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    // ── Role helpers ───────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    // ── Attendance helpers ─────────────────────────────────────────

    public function todayAttendance(): ?Attendance
    {
        return $this->attendances()
            ->whereDate('tanggal', today())
            ->first();
    }

    // ── Magang helpers ─────────────────────────────────────────────

    public function sisaHariMagang(): ?int
    {
        if (! $this->tanggal_selesai) return null;
        return max(0, today()->diffInDays($this->tanggal_selesai, false));
    }

    public function progressMagang(): int
    {
        if (! $this->tanggal_mulai || ! $this->tanggal_selesai) return 0;
        $total   = $this->tanggal_mulai->diffInDays($this->tanggal_selesai);
        $elapsed = $this->tanggal_mulai->diffInDays(today());
        if ($total <= 0) return 100;
        return min(100, (int) round(($elapsed / $total) * 100));
    }

    // ── Avatar ────────────────────────────────────────────────────

    /**
     * Prioritas: Google avatar → UI Avatars fallback.
     */
    public function getPhotoUrlAttribute(): string
    {
        return $this->avatar
            ?: 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=10b981&color=fff';
    }
}
