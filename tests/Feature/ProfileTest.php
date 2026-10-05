<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Di SQLite in-memory test, sesuaikan tabel users agar role string apa pun (termasuk 'guru')
        // dapat disimpan tanpa terhalang check constraint enum SQLite.
        if (config('database.default') === 'sqlite') {
            Schema::dropIfExists('users');
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->string('role')->default('siswa');
                $table->string('nis_nip')->nullable();
                $table->string('asal_sekolah')->nullable();
                $table->string('jurusan')->nullable();
                $table->date('tanggal_mulai')->nullable();
                $table->date('tanggal_selesai')->nullable();
                $table->string('phone')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

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
        $user = User::factory()->create([
            'name'  => 'Old Name',
            'phone' => '08111111111',
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->patch('/profile', [
                'name'  => 'Updated Name',
                'phone' => '08222222222',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile')
            ->assertSessionHas('status', 'Profil berhasil diperbarui.');

        $user->refresh();

        $this->assertSame('Updated Name', $user->name);
        $this->assertSame('08222222222', $user->phone);
    }

    public function test_email_cannot_be_updated_via_profile(): void
    {
        $originalEmail = 'original@example.com';
        $user = User::factory()->create([
            'email' => $originalEmail,
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->patch('/profile', [
                'name'  => 'Updated Name',
                'email' => 'newemail@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Updated Name', $user->name);
        $this->assertSame($originalEmail, $user->email);
    }

    public function test_user_can_logout_via_profile_destroy(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
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
            $user = User::factory()->create([
                'name'  => ucfirst($role) . ' User',
                'email' => "$role" . rand(10, 99) . "@azmedia.id",
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

    public function test_profile_displays_role_specific_content_for_each_role(): void
    {
        // 1. Siswa
        $siswa = User::factory()->create([
            'role'            => 'siswa',
            'asal_sekolah'    => 'SMKN 1 Malang',
            'jurusan'         => 'RPL',
            'nis_nip'         => '12345',
            'tanggal_mulai'   => now()->subDays(10),
            'tanggal_selesai' => now()->addDays(20),
        ]);
        $resSiswa = $this->actingAs($siswa)->get('/profile');
        $resSiswa->assertOk();
        $resSiswa->assertSee('Data Magang');
        $resSiswa->assertSee('SMKN 1 Malang');
        $resSiswa->assertSee('RPL');
        $resSiswa->assertSee('12345');

        // 2. Guru
        $guru = User::factory()->create([
            'role'         => 'guru',
            'asal_sekolah' => 'SMKN 2 Surabaya',
            'jurusan'      => 'TKJ',
            'nis_nip'      => '198001012005011001',
        ]);
        $resGuru = $this->actingAs($guru)->get('/profile');
        $resGuru->assertOk();
        $resGuru->assertSee('Informasi Guru Pembimbing');
        $resGuru->assertSee('SMKN 2 Surabaya');
        $resGuru->assertSee('198001012005011001');
        $resGuru->assertDontSee('Data Magang');

        // 3. Karyawan
        $karyawan = User::factory()->create([
            'role' => 'karyawan',
        ]);
        $resKaryawan = $this->actingAs($karyawan)->get('/profile');
        $resKaryawan->assertOk();
        $resKaryawan->assertSee('Informasi Akun');
        $resKaryawan->assertSee('Peran Akun');
        $resKaryawan->assertDontSee('AZ Media Corpora');
        $resKaryawan->assertDontSee('ID Karyawan');
        $resKaryawan->assertDontSee('Penugasan Pembimbing Lapangan');
        $resKaryawan->assertDontSee('Data Magang');

        // 4. Admin
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $resAdmin = $this->actingAs($admin)->get('/profile');
        $resAdmin->assertOk();
        $resAdmin->assertSee('Informasi Akun');
        $resAdmin->assertSee('Peran Akun');
        $resAdmin->assertDontSee('AZ Media Corpora');
        $resAdmin->assertDontSee('Data Magang');
    }
}
