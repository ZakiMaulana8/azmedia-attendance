<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test proteksi role admin pada route /admin/*.
 *
 * DB test = sqlite in-memory (phpunit.xml), database lokal tidak tersentuh.
 * User guru/siswa dibuat non-persisten karena enum role pada migrasi sqlite
 * belum memuat nilai `guru` (hanya relevan untuk test, bukan untuk middleware).
 */
class AdminRouteAccessTest extends TestCase
{
    use RefreshDatabase;
    private function userWithRole(string $role): User
    {
        return new User(['name' => ucfirst($role), 'email' => "$role@test.local", 'role' => $role]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/siswa')->assertRedirect(route('login'));
        $this->get('/admin/geofence')->assertRedirect(route('login'));
    }

    public function test_guru_and_siswa_are_forbidden_on_admin_get_pages(): void
    {
        foreach (['guru', 'siswa'] as $role) {
            $user = $this->userWithRole($role);

            foreach (['/admin/siswa', '/admin/guru', '/admin/geofence'] as $url) {
                $this->actingAs($user)->get($url)->assertForbidden();
            }
        }
    }

    public function test_guru_and_siswa_are_forbidden_on_admin_write_requests(): void
    {
        foreach (['guru', 'siswa'] as $role) {
            $user = $this->userWithRole($role);

            $this->actingAs($user)->post('/admin/geofence', [])->assertForbidden();
            $this->actingAs($user)->put('/admin/users/999999', [])->assertForbidden();
            $this->actingAs($user)->delete('/admin/users/999999')->assertForbidden();
        }
    }

    public function test_admin_is_not_blocked_by_role_middleware(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Yang diuji hanya lolos middleware (bukan 403 / redirect login). Status 500 di
        // environment test bisa muncul karena skema migrasi sqlite tidak identik dengan DB tim.
        foreach (['/admin/siswa', '/admin/guru', '/admin/geofence'] as $url) {
            $status = $this->actingAs($admin)->get($url)->getStatusCode();
            $this->assertNotContains($status, [301, 302, 401, 403, 404], "Admin ditolak di $url");
        }
    }
}
