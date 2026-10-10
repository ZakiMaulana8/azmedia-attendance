<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttendanceCheckOutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'geofence.nama_lokasi' => 'AZ Media',
            'geofence.latitude'    => -8.031133759026867,
            'geofence.longitude'   => 111.90199188567162,
            'geofence.radius'      => 100,
        ]);
    }

    public function test_checkout_page_can_be_accessed_by_siswa_who_has_checked_in(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        Attendance::create([
            'user_id'   => $user->id,
            'tanggal'   => today(),
            'jam_masuk' => '07:30:00',
            'status'    => 'hadir',
        ]);

        $response = $this->actingAs($user)->get(route('attendance.check-out'));

        $response->assertOk();
        $response->assertViewIs('attendance.check-out');
        $response->assertSee('Foto Absensi Pulang');
        $response->assertSee('Logbook Aktivitas Harian');
    }

    public function test_checkout_page_redirects_if_siswa_has_not_checked_in(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($user)->get(route('attendance.check-out'));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error', 'Anda belum melakukan absen masuk hari ini.');
    }

    public function test_checkout_page_redirects_if_siswa_already_checked_out(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        Attendance::create([
            'user_id'    => $user->id,
            'tanggal'    => today(),
            'jam_masuk'  => '07:30:00',
            'jam_pulang' => '16:05:00',
            'status'     => 'hadir',
        ]);

        $response = $this->actingAs($user)->get(route('attendance.check-out'));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error', 'Anda sudah absen pulang hari ini.');
    }

    public function test_checkout_page_forbidden_for_non_siswa(): void
    {
        $karyawan = User::factory()->create(['role' => 'karyawan']);

        $response = $this->actingAs($karyawan)->get(route('attendance.check-out'));

        $response->assertForbidden();
    }

    public function test_checkout_fails_if_no_check_in_record_exists(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($user)->postJson(route('attendance.store'), [
            'latitude'  => -8.031133759026867,
            'longitude' => 111.90199188567162,
            'type'      => 'pulang',
            'logbook'   => 'Mempelajari arsitektur Laravel dan testing API secara mandiri.',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Anda belum absen masuk hari ini.',
        ]);
    }

    public function test_checkout_fails_if_logbook_less_than_20_characters(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        Attendance::create([
            'user_id'   => $user->id,
            'tanggal'   => today(),
            'jam_masuk' => '07:30:00',
            'status'    => 'hadir',
        ]);

        // String kurang dari 20 karakter
        $response = $this->actingAs($user)->postJson(route('attendance.store'), [
            'latitude'  => -8.031133759026867,
            'longitude' => 111.90199188567162,
            'type'      => 'pulang',
            'logbook'   => 'Terlalu pendek',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['logbook']);

        // String dengan whitespace yang jika di-trim kurang dari 20 karakter
        $response2 = $this->actingAs($user)->postJson(route('attendance.store'), [
            'latitude'  => -8.031133759026867,
            'longitude' => 111.90199188567162,
            'type'      => 'pulang',
            'logbook'   => '       1234567890       ',
        ]);

        $response2->assertStatus(422);
        $response2->assertJsonValidationErrors(['logbook']);
    }

    public function test_checkout_succeeds_with_logbook_at_least_20_characters(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        Attendance::create([
            'user_id'   => $user->id,
            'tanggal'   => today(),
            'jam_masuk' => '07:30:00',
            'status'    => 'hadir',
        ]);

        $response = $this->actingAs($user)->postJson(route('attendance.store'), [
            'latitude'  => -8.031133759026867,
            'longitude' => 111.90199188567162,
            'type'      => 'pulang',
            'logbook'   => 'Menyelesaikan modul logbook dan fitur foto absensi pulang hari ini.',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $attendance = Attendance::where('user_id', $user->id)->first();
        $this->assertNotNull($attendance->jam_pulang);
        $this->assertEquals('Menyelesaikan modul logbook dan fitur foto absensi pulang hari ini.', $attendance->logbook);
    }

    public function test_checkout_saves_foto_pulang_and_preserves_foto_masuk(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'siswa']);

        $attendance = Attendance::create([
            'user_id'    => $user->id,
            'tanggal'    => today(),
            'jam_masuk'  => '07:30:00',
            'foto_masuk' => 'attendance/masuk/' . $user->id . '/masuk_original.jpg',
            'status'     => 'hadir',
        ]);

        $fakePhoto = UploadedFile::fake()->image('absen-pulang.jpg');

        $response = $this->actingAs($user)->postJson(route('attendance.store'), [
            'latitude'  => -8.031133759026867,
            'longitude' => 111.90199188567162,
            'type'      => 'pulang',
            'foto'      => $fakePhoto,
            'logbook'   => 'Mengerjakan perbaikan sistem absensi dan dokumentasi fitur pulang.',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $fresh = $attendance->fresh();
        $this->assertNotNull($fresh->foto_pulang);
        $this->assertStringStartsWith('attendance/pulang/' . $user->id . '/', $fresh->foto_pulang);
        $this->assertEquals('attendance/masuk/' . $user->id . '/masuk_original.jpg', $fresh->foto_masuk);
        Storage::disk('public')->assertExists($fresh->foto_pulang);
    }

    public function test_checkout_fails_outside_geofence(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        Attendance::create([
            'user_id'   => $user->id,
            'tanggal'   => today(),
            'jam_masuk' => '07:30:00',
            'status'    => 'hadir',
        ]);

        $response = $this->actingAs($user)->postJson(route('attendance.store'), [
            'latitude'  => -6.2088,
            'longitude' => 106.8456,
            'type'      => 'pulang',
            'logbook'   => 'Mencoba absensi di luar kantor dengan teks yang cukup panjang.',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('Anda berada di luar area AZ Media', $response->json('message'));
    }

    public function test_checkout_cannot_be_submitted_twice(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        Attendance::create([
            'user_id'    => $user->id,
            'tanggal'    => today(),
            'jam_masuk'  => '07:30:00',
            'jam_pulang' => '16:15:00',
            'status'     => 'hadir',
        ]);

        $response = $this->actingAs($user)->postJson(route('attendance.store'), [
            'latitude'  => -8.031133759026867,
            'longitude' => 111.90199188567162,
            'type'      => 'pulang',
            'logbook'   => 'Mencoba absen pulang untuk kedua kalinya hari ini.',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Anda sudah absen pulang hari ini.',
        ]);
    }

    public function test_checkin_page_and_process_remain_functional(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($user)->get(route('attendance.check-in'));
        $response->assertOk();
        $response->assertViewIs('attendance.check-in');

        $responsePost = $this->actingAs($user)->postJson(route('attendance.store'), [
            'latitude'  => -8.031133759026867,
            'longitude' => 111.90199188567162,
            'type'      => 'masuk',
        ]);

        $responsePost->assertOk();
        $responsePost->assertJson([
            'success' => true,
        ]);
    }
}
