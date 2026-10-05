<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PermissionApprovalAttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Di SQLite in-memory test, sesuaikan tabel agar role 'guru'
        // dan tabel 'attendances' berjalan sempurna tanpa menyentuh migrasi/database MySQL.
        if (config('database.default') === 'sqlite') {
            Schema::dropIfExists('permissions');
            Schema::dropIfExists('attendances');
            Schema::dropIfExists('users');

            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
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

            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('jenis');
                $table->date('tanggal');
                $table->text('alasan');
                $table->string('lampiran')->nullable();
                $table->string('status')->default('menunggu');
                $table->text('catatan_approver')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
            });

            Schema::create('attendances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
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
                $table->unique(['user_id', 'tanggal']);
            });
        }
    }

    private function createGuru(): User
    {
        return User::factory()->create([
            'role' => 'guru',
        ]);
    }

    private function createSiswa(): User
    {
        return User::factory()->create([
            'role' => 'siswa',
        ]);
    }

    /**
     * Test 1: approve → permission `disetujui` + attendance `izin`
     */
    public function test_approve_creates_attendance_with_status_izin(): void
    {
        $guru  = $this->createGuru();
        $siswa = $this->createSiswa();

        $permission = Permission::create([
            'user_id'  => $siswa->id,
            'jenis'    => 'sakit',
            'tanggal'  => '2026-10-10',
            'alasan'   => 'Demam tinggi',
            'status'   => 'menunggu',
        ]);

        $response = $this->actingAs($guru)->patchJson(route('permissions.approve', $permission), [
            'action'  => 'disetujui',
            'catatan' => 'Semoga lekas sembuh',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'status'  => 'disetujui',
        ]);

        // Cek permission terupdate
        $permission->refresh();
        $this->assertEquals('disetujui', $permission->status);
        $this->assertEquals($guru->id, $permission->approver_id);
        $this->assertEquals('Semoga lekas sembuh', $permission->catatan_approver);
        $this->assertNotNull($permission->approved_at);

        // Cek attendance otomatis terbuat dengan status 'izin'
        $attendance = Attendance::where('user_id', $siswa->id)
            ->whereDate('tanggal', '2026-10-10')
            ->first();

        $this->assertNotNull($attendance);
        $this->assertEquals('izin', $attendance->status);
        $this->assertEquals('Izin Sakit', $attendance->keterangan);
    }

    /**
     * Test 2: pending → tidak ada attendance `izin`
     */
    public function test_pending_permission_does_not_create_attendance(): void
    {
        $siswa = $this->createSiswa();

        Permission::create([
            'user_id'  => $siswa->id,
            'jenis'    => 'izin',
            'tanggal'  => '2026-10-11',
            'alasan'   => 'Acara keluarga',
            'status'   => 'menunggu',
        ]);

        $attendance = Attendance::where('user_id', $siswa->id)
            ->whereDate('tanggal', '2026-10-11')
            ->first();

        $this->assertNull($attendance);
    }

    /**
     * Test 3: reject → tidak ada attendance `izin`
     */
    public function test_reject_permission_does_not_create_attendance(): void
    {
        $guru  = $this->createGuru();
        $siswa = $this->createSiswa();

        $permission = Permission::create([
            'user_id'  => $siswa->id,
            'jenis'    => 'izin',
            'tanggal'  => '2026-10-12',
            'alasan'   => 'Keperluan pribadi',
            'status'   => 'menunggu',
        ]);

        $response = $this->actingAs($guru)->patchJson(route('permissions.approve', $permission), [
            'action'  => 'ditolak',
            'catatan' => 'Alasan kurang jelas',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'status'  => 'ditolak',
        ]);

        $permission->refresh();
        $this->assertEquals('ditolak', $permission->status);

        // Pastikan tidak ada record attendance
        $attendance = Attendance::where('user_id', $siswa->id)
            ->whereDate('tanggal', '2026-10-12')
            ->first();

        $this->assertNull($attendance);
    }

    /**
     * Test 4: approve dua kali → tidak membuat duplicate
     */
    public function test_approve_twice_does_not_create_duplicate_attendance(): void
    {
        $guru  = $this->createGuru();
        $siswa = $this->createSiswa();

        $permission = Permission::create([
            'user_id'  => $siswa->id,
            'jenis'    => 'dispensasi',
            'tanggal'  => '2026-10-13',
            'alasan'   => 'Lomba LKS',
            'status'   => 'menunggu',
        ]);

        // Approval pertama
        $this->actingAs($guru)->patchJson(route('permissions.approve', $permission), [
            'action' => 'disetujui',
        ])->assertOk();

        // Approval kedua (misal klik ulang)
        $this->actingAs($guru)->patchJson(route('permissions.approve', $permission), [
            'action' => 'disetujui',
        ])->assertOk();

        $count = Attendance::where('user_id', $siswa->id)
            ->whereDate('tanggal', '2026-10-13')
            ->count();

        $this->assertEquals(1, $count);
    }

    /**
     * Test 5: existing attendance → data attendance hadir tidak rusak
     */
    public function test_existing_attendance_with_checkin_is_not_corrupted_on_approval(): void
    {
        $guru  = $this->createGuru();
        $siswa = $this->createSiswa();

        // Siswa ternyata sudah sempat absen hadir di kantor
        $existing = Attendance::create([
            'user_id'     => $siswa->id,
            'tanggal'     => '2026-10-14',
            'jam_masuk'   => '07:15:00',
            'lat_masuk'   => -8.0311337,
            'lng_masuk'   => 111.9019918,
            'jarak_masuk' => 12.50,
            'status'      => 'hadir',
            'keterangan'  => 'Hadir tepat waktu',
        ]);

        $permission = Permission::create([
            'user_id'  => $siswa->id,
            'jenis'    => 'izin',
            'tanggal'  => '2026-10-14',
            'alasan'   => 'Izin pulang awal',
            'status'   => 'menunggu',
        ]);

        $response = $this->actingAs($guru)->patchJson(route('permissions.approve', $permission), [
            'action' => 'disetujui',
        ]);

        $response->assertOk();

        // Cek bahwa data attendance lama tidak tertimpa/rusak
        $attendance = Attendance::where('user_id', $siswa->id)
            ->whereDate('tanggal', '2026-10-14')
            ->first();

        $this->assertEquals(1, Attendance::where('user_id', $siswa->id)->whereDate('tanggal', '2026-10-14')->count());
        $this->assertEquals('hadir', $attendance->status);
        $this->assertEquals('07:15', $attendance->jam_masuk->format('H:i'));
        $this->assertEquals(-8.0311337, (float) $attendance->lat_masuk);
        $this->assertEquals('Hadir tepat waktu', $attendance->keterangan);
    }
}
