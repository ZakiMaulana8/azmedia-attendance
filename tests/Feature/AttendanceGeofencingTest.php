<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Geofence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceGeofencingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Pastikan config load default terpasang
        config([
            'geofence.nama_lokasi' => 'AZ Media',
            'geofence.latitude'    => -8.031133759026867,
            'geofence.longitude'   => 111.90199188567162,
            'geofence.radius'      => 100,
        ]);
    }

    public function test_attendance_success_within_radius(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        // Koordinat persis/sangat dekat (jarak ~0m)
        $response = $this->actingAs($user)->postJson(route('attendance.store'), [
            'latitude'  => -8.031133759026867,
            'longitude' => 111.90199188567162,
            'type'      => 'masuk',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Absen masuk berhasil!',
        ]);

        $attendance = Attendance::first();
        $this->assertNotNull($attendance);
        $this->assertEquals($user->id, $attendance->user_id);
        $this->assertEqualsWithDelta(-8.031133759026867, (float) $attendance->lat_masuk, 0.0001);
        $this->assertEqualsWithDelta(111.90199188567162, (float) $attendance->lng_masuk, 0.0001);
    }

    public function test_attendance_fails_outside_radius(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        // Koordinat jauh (misal Jakarta)
        $response = $this->actingAs($user)->postJson(route('attendance.store'), [
            'latitude'  => -6.2088,
            'longitude' => 106.8456,
            'type'      => 'masuk',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Anda berada di luar area AZ Media. Silakan lakukan absensi di lokasi kantor.',
        ]);

        $this->assertDatabaseMissing('attendances', [
            'user_id' => $user->id,
        ]);
    }

    public function test_attendance_uses_active_geofence_from_database_if_present(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        // Set geofence aktif baru di database (misal di Monas, Jakarta)
        Geofence::create([
            'nama_lokasi' => 'Kantor Cabang Jakarta',
            'latitude'    => -6.175392,
            'longitude'   => 106.827153,
            'radius'      => 100,
            'is_active'   => true,
        ]);

        // Pengguna di Jakarta (Monas) harus sukses
        $response1 = $this->actingAs($user)->postJson(route('attendance.store'), [
            'latitude'  => -6.175392,
            'longitude' => 106.827153,
            'type'      => 'masuk',
        ]);
        $response1->assertOk();

        // Pengguna di Tulungagung (kantor pusat) harus gagal karena geofence aktif di Jakarta
        $user2 = User::factory()->create(['role' => 'siswa']);
        $response2 = $this->actingAs($user2)->postJson(route('attendance.store'), [
            'latitude'  => -8.031133759026867,
            'longitude' => 111.90199188567162,
            'type'      => 'masuk',
        ]);
        $response2->assertStatus(422);
        $response2->assertJson([
            'success' => false,
            'message' => 'Anda berada di luar area Kantor Cabang Jakarta. Silakan lakukan absensi di lokasi kantor.',
        ]);
    }

    public function test_checkout_before_16_saves_pulang_cepat(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        // Check-in dulu
        Attendance::create([
            'user_id' => $user->id,
            'tanggal' => today(),
            'jam_masuk' => '07:00:00',
            'status' => 'hadir',
        ]);

        // Mock waktu ke pukul 15:30 WIB
        \Carbon\Carbon::setTestNow(today()->setTime(15, 30));

        $response = $this->actingAs($user)->postJson(route('attendance.store'), [
            'latitude'  => -8.031133759026867,
            'longitude' => 111.90199188567162,
            'type'      => 'pulang',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'keterangan' => 'Pulang Cepat',
        ]);

        $this->assertEquals('Pulang Cepat', Attendance::first()->keterangan);
        
        \Carbon\Carbon::setTestNow(); // reset mock
    }

    public function test_checkout_after_16_saves_pulang_tepat_waktu(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        // Check-in dulu
        Attendance::create([
            'user_id' => $user->id,
            'tanggal' => today(),
            'jam_masuk' => '07:00:00',
            'status' => 'hadir',
        ]);

        // Mock waktu ke pukul 16:01 WIB
        \Carbon\Carbon::setTestNow(today()->setTime(16, 1));

        $response = $this->actingAs($user)->postJson(route('attendance.store'), [
            'latitude'  => -8.031133759026867,
            'longitude' => 111.90199188567162,
            'type'      => 'pulang',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'keterangan' => 'Pulang Tepat Waktu',
        ]);

        $this->assertEquals('Pulang Tepat Waktu', Attendance::first()->keterangan);

        \Carbon\Carbon::setTestNow(); // reset mock
    }
}
