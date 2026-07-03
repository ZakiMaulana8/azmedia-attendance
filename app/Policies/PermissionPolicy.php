<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
    /**
     * Hanya admin dan guru yang bisa menyetujui / menolak izin.
     */
    public function update(User $user, Permission $permission): bool
    {
        return $user->isAdmin() || $user->isGuru();
    }
}
