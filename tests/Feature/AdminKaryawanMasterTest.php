<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test Data Master Karyawan (/admin/karyawan).
 *
 * DB test = sqlite in-memory; enum role di sqlite belum memuat `guru`,
 * sehingga user guru hanya dibuat non-persisten (cukup untuk uji middleware).
 */
class AdminKaryawanMasterTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_open_karyawan_master_page(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/karyawan')
            ->assertOk()
            ->assertViewIs('admin.karyawan.index')
            ->assertSee('Data Master → Karyawan', false);
    }

    public function test_only_karyawan_role_is_listed(): void
    {
        User::factory()->create(['role' => 'karyawan', 'name' => 'Karyawan Satu', 'email' => 'k1@test.local']);
        User::factory()->create(['role' => 'siswa', 'name' => 'Siswa Tidak Tampil', 'email' => 's1@test.local']);
        User::factory()->create(['role' => 'admin', 'name' => 'Admin Lain Tidak Tampil', 'email' => 'a1@test.local']);

        $this->actingAs($this->admin())
            ->get('/admin/karyawan')
            ->assertOk()
            ->assertSee('Karyawan Satu')
            ->assertDontSee('Siswa Tidak Tampil')
            ->assertDontSee('Admin Lain Tidak Tampil');
    }

    public function test_search_filters_karyawan(): void
    {
        User::factory()->create(['role' => 'karyawan', 'name' => 'Andi Karyawan', 'email' => 'andi@test.local']);
        User::factory()->create(['role' => 'karyawan', 'name' => 'Bima Karyawan', 'email' => 'bima@test.local']);

        $this->actingAs($this->admin())
            ->get('/admin/karyawan?search=Andi')
            ->assertSee('Andi Karyawan')
            ->assertDontSee('Bima Karyawan');
    }

    public function test_admin_can_change_user_role_to_karyawan(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'name' => 'Calon Karyawan', 'email' => 'calon@test.local']);

        $this->actingAs($this->admin())
            ->put("/admin/users/{$siswa->id}", [
                'name'  => 'Calon Karyawan',
                'email' => 'calon@test.local',
                'role'  => 'karyawan',
            ])
            ->assertRedirect();

        $this->assertSame('karyawan', $siswa->fresh()->role);
    }

    public function test_guru_siswa_and_karyawan_cannot_open_karyawan_master(): void
    {
        foreach (['guru', 'siswa', 'karyawan'] as $role) {
            $user = new User(['name' => ucfirst($role), 'email' => "$role@test.local", 'role' => $role]);

            $this->actingAs($user)->get('/admin/karyawan')->assertForbidden();
        }
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/karyawan')->assertRedirect(route('login'));
    }

    public function test_invalid_role_is_rejected_on_update(): void
    {
        $target = User::factory()->create(['role' => 'siswa', 'email' => 'target@test.local']);

        foreach (['superadmin', 'guru_pembimbing', ''] as $badRole) {
            $this->actingAs($this->admin())
                ->put("/admin/users/{$target->id}", [
                    'name'  => 'Target',
                    'email' => 'target@test.local',
                    'role'  => $badRole,
                ])
                ->assertSessionHasErrors('role');
        }

        $this->assertSame('siswa', $target->fresh()->role);
    }

    public function test_existing_siswa_update_and_delete_still_work(): void
    {
        $admin = $this->admin();
        $siswa = User::factory()->create(['role' => 'siswa', 'name' => 'Siswa Lama', 'email' => 'lama@test.local']);

        $this->actingAs($admin)
            ->put("/admin/users/{$siswa->id}", [
                'name'  => 'Siswa Baru',
                'email' => 'lama@test.local',
                'role'  => 'siswa',
                'phone' => '0811',
            ])
            ->assertRedirect();

        $this->assertSame('Siswa Baru', $siswa->fresh()->name);
        $this->assertSame('siswa', $siswa->fresh()->role);

        $this->actingAs($admin)->delete("/admin/users/{$siswa->id}")->assertRedirect();
        $this->assertNull(User::find($siswa->id));
    }

    public function test_karyawan_page_has_nav_link_consistent_with_siswa_and_guru(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/karyawan')
            ->assertSee(route('admin.siswa.index'), false)
            ->assertSee(route('admin.guru.index'), false)
            ->assertSee(route('admin.karyawan.index'), false);
    }
}
