<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('google_id')->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(); // nullable karena login via Google
            $table->enum('role', ['admin', 'siswa', 'karyawan', 'guru_pembimbing'])->default('siswa');
            $table->string('nis_nip')->nullable()->comment('NIS untuk siswa magang, NIP untuk guru pembimbing');
            $table->string('asal_sekolah')->nullable()->comment('Nama sekolah asal siswa magang');
            $table->string('jurusan')->nullable()->comment('Jurusan siswa magang');
            $table->date('tanggal_mulai')->nullable()->comment('DEPRECATED — gunakan penempatan_pkl.tanggal_mulai');
            $table->date('tanggal_selesai')->nullable()->comment('DEPRECATED — gunakan penempatan_pkl.tanggal_selesai');
            $table->string('phone')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
