<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleKaryawanTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_karyawan_recognized_by_user_model_and_helpers(): void
    {
        $user = new User([
            'name'  => 'Pembimbing Lapangan',
            'email' => 'karyawan@azmedia.id',
            'role'  => 'karyawan',
        ]);

        $this->assertSame('karyawan', $user->role);
        $this->assertTrue($user->isKaryawan(), 'isKaryawan() harus true untuk user role karyawan');
        $this->assertFalse($user->isAdmin(), 'isAdmin() harus false untuk user role karyawan');
        $this->assertFalse($user->isGuru(), 'isGuru() harus false untuk user role karyawan');
        $this->assertFalse($user->isSiswa(), 'isSiswa() harus false untuk user role karyawan');
    }

    public function test_karyawan_cannot_access_admin_routes(): void
    {
        $karyawan = new User([
            'id'    => 55,
            'name'  => 'Karyawan Test',
            'email' => 'karyawan@azmedia.id',
            'role'  => 'karyawan',
        ]);

        // GET routes admin
        foreach (['/admin/siswa', '/admin/guru', '/admin/geofence'] as $url) {
            $this->actingAs($karyawan)->get($url)->assertForbidden();
        }

        // Mutation / Write routes admin
        $this->actingAs($karyawan)->post('/admin/geofence', [])->assertForbidden();
        $this->actingAs($karyawan)->put('/admin/users/1', [])->assertForbidden();
        $this->actingAs($karyawan)->delete('/admin/users/1')->assertForbidden();
    }

    public function test_karyawan_dashboard_does_not_render_siswa_guru_or_admin_dashboard(): void
    {
        $karyawan = new User([
            'id'    => 55,
            'name'  => 'Karyawan Test',
            'email' => 'karyawan@azmedia.id',
            'role'  => 'karyawan',
        ]);

        $response = $this->actingAs($karyawan)->get('/dashboard');

        $response->assertOk();
        $response->assertDontSee('Siswa Magang');
        $response->assertDontSee('Radius Geofence');
        $response->assertSee('Dashboard Karyawan sedang dalam pengembangan.');
    }
}
