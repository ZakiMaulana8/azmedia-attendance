<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    public function test_profile_displays_correct_role_labels_and_subtitles(): void
    {
        $rolesAndLabels = [
            'siswa' => [
                'roleLabel' => 'Siswa Magang',
                'subtitle'  => 'Informasi akun dan data magang',
            ],
            'guru' => [
                'roleLabel' => 'Guru Pembimbing',
                'subtitle'  => 'Informasi akun dan profil guru pembimbing',
            ],
            'karyawan' => [
                'roleLabel' => 'Karyawan',
                'subtitle'  => 'Informasi akun dan profil karyawan',
            ],
            'admin' => [
                'roleLabel' => 'Administrator',
                'subtitle'  => 'Informasi akun dan hak akses administrator',
            ],
        ];

        foreach ($rolesAndLabels as $role => $expected) {
            $user = new User([
                'id'    => rand(100, 999),
                'name'  => ucfirst($role) . ' User',
                'email' => "$role@azmedia.id",
                'role'  => $role,
            ]);

            $response = $this->actingAs($user)->get('/profile');

            $response->assertOk();
            $response->assertSee($expected['roleLabel']);
            $response->assertSee($expected['subtitle']);

            // Non-siswa tidak boleh melihat bagian 'Data Magang'
            if ($role !== 'siswa') {
                $response->assertDontSee('Data Magang');
                $response->assertDontSee('Informasi akun dan data magang');
            }
        }
    }
}
