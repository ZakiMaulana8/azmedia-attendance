<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RoleKaryawanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            if (! Schema::hasTable('attendances')) {
                Schema::create('attendances', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('user_id')->nullable();
                    $table->date('tanggal');
                    $table->time('jam_masuk')->nullable();
                    $table->time('jam_pulang')->nullable();
                    $table->string('foto_masuk')->nullable();
                    $table->string('foto_pulang')->nullable();
                    $table->decimal('lat_masuk', 10, 7)->nullable();
                    $table->decimal('lng_masuk', 10, 7)->nullable();
                    $table->decimal('lat_pulang', 10, 7)->nullable();
                    $table->decimal('lng_pulang', 10, 7)->nullable();
                    $table->decimal('jarak_masuk', 8, 2)->nullable();
                    $table->decimal('jarak_pulang', 8, 2)->nullable();
                    $table->text('logbook')->nullable();
                    $table->string('status')->default('hadir');
                    $table->string('keterangan')->nullable();
                    $table->timestamps();
                });
            }

            if (! Schema::hasTable('geofences')) {
                Schema::create('geofences', function (Blueprint $table) {
                    $table->id();
                    $table->string('nama_lokasi');
                    $table->decimal('latitude', 10, 7);
                    $table->decimal('longitude', 10, 7);
                    $table->unsignedInteger('radius')->default(100);
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });
            }
        }
    }

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

    public function test_karyawan_dashboard_renders_karyawan_view(): void
    {
        $karyawan = new User([
            'id'    => 55,
            'name'  => 'Karyawan Test',
            'email' => 'karyawan@azmedia.id',
            'role'  => 'karyawan',
        ]);

        $response = $this->actingAs($karyawan)->get('/dashboard');

        $response->assertOk();
        $response->assertViewIs('dashboard.karyawan');
        $response->assertSee('Halo, Karyawan Test!');
        $response->assertSee('Pembimbing Lapangan');
        $response->assertSee('Ringkasan Siswa PKL Hari Ini');
        $response->assertSee('Daftar Siswa PKL Hari Ini');
        $response->assertSee('Total Siswa');
        $response->assertSee('Sudah Absen');
        $response->assertSee('Belum Absen');
        $response->assertSee('Izin');
        $response->assertSee('Alpa');

        // Memastikan elemen khas dashboard admin/siswa tidak tampil
        $response->assertDontSee('Radius Geofence');
        $response->assertDontSee('Layanan Cepat');
    }

    public function test_admin_guru_and_siswa_still_get_their_respective_dashboards(): void
    {
        $admin = new User(['id' => 1, 'name' => 'Admin User', 'email' => 'admin@azmedia.id', 'role' => 'admin']);
        $this->actingAs($admin)->get('/dashboard')
            ->assertOk()
            ->assertViewIs('admin.dashboard');

        $guru = new User(['id' => 2, 'name' => 'Guru User', 'email' => 'guru@azmedia.id', 'role' => 'guru']);
        $this->actingAs($guru)->get('/dashboard')
            ->assertOk()
            ->assertViewIs('dashboard.guru');

        $siswa = new User(['id' => 3, 'name' => 'Siswa User', 'email' => 'siswa@azmedia.id', 'role' => 'siswa']);
        $this->actingAs($siswa)->get('/dashboard')
            ->assertOk()
            ->assertViewIs('dashboard.siswa');
    }

    public function test_karyawan_dashboard_displays_real_student_attendance_data(): void
    {
        $karyawan = new User([
            'id'    => 55,
            'name'  => 'Karyawan Test',
            'email' => 'karyawan@azmedia.id',
            'role'  => 'karyawan',
        ]);

        $siswa1 = User::create([
            'name'            => 'Ahmad Siswa',
            'email'           => 'ahmad@test.local',
            'role'            => 'siswa',
            'jurusan'         => 'RPL',
            'tanggal_mulai'   => today()->subMonth(),
            'tanggal_selesai' => today()->addMonth(),
        ]);

        $siswa2 = User::create([
            'name'            => 'Budi Siswa',
            'email'           => 'budi@test.local',
            'role'            => 'siswa',
            'jurusan'         => 'TKJ',
            'tanggal_mulai'   => today()->subMonth(),
            'tanggal_selesai' => today()->addMonth(),
        ]);

        $siswa3 = User::create([
            'name'            => 'Citra Siswa',
            'email'           => 'citra@test.local',
            'role'            => 'siswa',
            'jurusan'         => 'MM',
            'tanggal_mulai'   => today()->subMonth(),
            'tanggal_selesai' => today()->addMonth(),
        ]);

        $siswa4 = User::create([
            'name'            => 'Doni Siswa',
            'email'           => 'doni@test.local',
            'role'            => 'siswa',
            'jurusan'         => 'DKV',
            'tanggal_mulai'   => today()->subMonth(),
            'tanggal_selesai' => today()->addMonth(),
        ]);

        // Siswa 1: Hadir (08:00 - 16:00)
        Attendance::create([
            'user_id'    => $siswa1->id,
            'tanggal'    => today(),
            'jam_masuk'  => '08:00',
            'jam_pulang' => '16:00',
            'status'     => 'hadir',
        ]);

        // Siswa 2: Izin
        Attendance::create([
            'user_id' => $siswa2->id,
            'tanggal' => today(),
            'status'  => 'izin',
        ]);

        // Siswa 3: Alpa
        Attendance::create([
            'user_id' => $siswa3->id,
            'tanggal' => today(),
            'status'  => 'alpa',
        ]);

        // Siswa 4: Belum Absen (tidak ada attendance)

        $response = $this->actingAs($karyawan)->get('/dashboard');

        $response->assertOk();
        $response->assertViewIs('dashboard.karyawan');

        // Cek data siswa tampil
        $response->assertSee('Ahmad Siswa');
        $response->assertSee('Budi Siswa');
        $response->assertSee('Citra Siswa');
        $response->assertSee('Doni Siswa');

        // Cek jurusan
        $response->assertSee('RPL');
        $response->assertSee('TKJ');
        $response->assertSee('MM');
        $response->assertSee('DKV');

        // Cek jam masuk & pulang siswa hadir
        $response->assertSee('08:00');
        $response->assertSee('16:00');

        // Cek label status
        $response->assertSee('Hadir');
        $response->assertSee('Izin');
        $response->assertSee('Alpa');
        $response->assertSee('Belum Absen');
    }

    public function test_karyawan_can_access_siswa_pkl_page_with_real_attendance_data(): void
    {
        $karyawan = new User([
            'id'    => 55,
            'name'  => 'Karyawan Test',
            'email' => 'karyawan@azmedia.id',
            'role'  => 'karyawan',
        ]);

        $siswa = User::create([
            'name'            => 'Eka Siswa',
            'email'           => 'eka@test.local',
            'role'            => 'siswa',
            'jurusan'         => 'RPL',
            'asal_sekolah'    => 'SMKN 1 Tulungagung',
            'tanggal_mulai'   => today()->subMonth(),
            'tanggal_selesai' => today()->addMonth(),
        ]);

        Attendance::create([
            'user_id'    => $siswa->id,
            'tanggal'    => today(),
            'jam_masuk'  => '07:45',
            'jam_pulang' => '16:15',
            'status'     => 'hadir',
        ]);

        $response = $this->actingAs($karyawan)->get('/karyawan/siswa');

        $response->assertOk();
        $response->assertViewIs('karyawan.siswa.index');
        $response->assertSee('Daftar Siswa PKL');
        $response->assertSee('Eka Siswa');
        $response->assertSee('RPL');
        $response->assertSee('SMKN 1 Tulungagung');
        $response->assertSee('07:45');
        $response->assertSee('16:15');
        $response->assertSee('Hadir');

        // Read-only: pastikan tidak ada aksi edit atau hapus
        $response->assertDontSee('admin.users.destroy');
        $response->assertDontSee('admin.users.update');
    }

    public function test_guest_is_redirected_to_login_on_karyawan_siswa_page(): void
    {
        $this->get('/karyawan/siswa')->assertRedirect(route('login'));
    }

    public function test_siswa_is_forbidden_on_karyawan_siswa_page(): void
    {
        $siswa = new User([
            'id'    => 66,
            'name'  => 'Siswa Test',
            'email' => 'siswa@test.local',
            'role'  => 'siswa',
        ]);

        $this->actingAs($siswa)->get('/karyawan/siswa')->assertForbidden();
    }

    public function test_guru_and_admin_existing_features_are_unaffected(): void
    {
        // Guru tetap mengakses dashboard guru mereka
        $guru = new User(['id' => 77, 'name' => 'Guru Test', 'email' => 'guru@test.local', 'role' => 'guru']);
        $this->actingAs($guru)->get('/dashboard')->assertOk()->assertViewIs('dashboard.guru');

        // Admin tetap mengakses dashboard admin mereka
        $admin = new User(['id' => 88, 'name' => 'Admin Test', 'email' => 'admin@test.local', 'role' => 'admin']);
        $this->actingAs($admin)->get('/dashboard')->assertOk()->assertViewIs('admin.dashboard');
    }
}
