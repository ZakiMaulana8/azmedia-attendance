<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
    /**
     * Hanya admin, guru, dan karyawan yang bisa melihat daftar approval izin siswa.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isGuru() || $user->isKaryawan();
    }

    /**
     * Hanya admin, guru, dan karyawan yang bisa menyetujui / menolak izin.
     */
    public function update(User $user, Permission $permission): bool
    {
        return $user->isAdmin() || $user->isGuru() || $user->isKaryawan();
    }
}
