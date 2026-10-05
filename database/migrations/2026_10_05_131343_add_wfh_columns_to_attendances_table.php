<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Tipe absen: wfo = kantor, wfh = rumah
            $table->enum('tipe_absen', ['wfo', 'wfh'])->default('wfo')->after('user_id');
            // Logbook kegiatan harian (wajib saat check-out)
            $table->text('logbook')->nullable()->after('keterangan');
            // Jarak dari geofence
            $table->decimal('jarak_masuk', 8, 2)->nullable()->after('lng_masuk')->comment('Jarak dari geofence saat masuk (meter)');
            $table->decimal('jarak_pulang', 8, 2)->nullable()->after('lng_pulang')->comment('Jarak dari geofence saat pulang (meter)');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['tipe_absen', 'logbook', 'jarak_masuk', 'jarak_pulang']);
        });
    }
};
