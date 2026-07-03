<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // File lampiran (bisa lebih dari satu per laporan)
        Schema::create('activity_report_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_report_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('original_name');   // nama file asli
            $table->string('path');            // path di storage
            $table->string('mime_type');       // image/jpeg, video/mp4, dll
            $table->unsignedBigInteger('size'); // bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_report_files');
        Schema::dropIfExists('activity_reports');
    }
};
