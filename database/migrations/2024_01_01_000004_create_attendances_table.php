<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->string('foto_masuk')->nullable()->comment('Path foto wajah saat check-in');
            $table->string('foto_pulang')->nullable()->comment('Path foto wajah saat check-out');
            $table->decimal('lat_masuk', 10, 7)->nullable();
            $table->decimal('lng_masuk', 10, 7)->nullable();
            $table->decimal('lat_pulang', 10, 7)->nullable();
            $table->decimal('lng_pulang', 10, 7)->nullable();
            $table->decimal('jarak_masuk', 8, 2)->nullable()->comment('Jarak dari geofence dalam meter saat check-in');
            $table->decimal('jarak_pulang', 8, 2)->nullable()->comment('Jarak dari geofence dalam meter saat check-out');
            $table->text('logbook')->nullable()->comment('Logbook activity wajib diisi saat check-out');
            $table->enum('status', ['hadir', 'izin', 'alpa'])->default('hadir');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'tanggal']);
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
