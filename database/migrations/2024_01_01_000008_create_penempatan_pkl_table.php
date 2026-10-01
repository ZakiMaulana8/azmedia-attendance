<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penempatan_pkl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete()
                  ->comment('Siswa yang ditempatkan');
            $table->foreignId('periode_pkl_id')
                  ->constrained('periode_pkl')
                  ->cascadeOnDelete();
            $table->foreignId('guru_pembimbing_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->comment('Guru pembimbing yang ditugaskan');
            $table->date('tanggal_mulai')->comment('Tanggal mulai individual siswa');
            $table->date('tanggal_selesai')->comment('Tanggal selesai individual siswa');
            $table->timestamps();

            $table->unique(['user_id', 'periode_pkl_id']);
            $table->index('guru_pembimbing_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penempatan_pkl');
    }
};
