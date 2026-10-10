@extends('layouts.app')
@section('title', 'Foto Absensi Pulang')

@push('styles')
<style>
    .logbook-textarea-field {
        display: block !important;
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        min-height: 130px;
        height: 130px;
        padding: 14px 16px !important;
        border-radius: 16px !important;
        font-size: 13.5px !important;
        line-height: 1.6 !important;
        color: #0F172A !important;
        background-color: #FFFFFF !important;
        border: 1.5px solid #CBD5E1 !important;
        outline: none !important;
        resize: vertical;
        font-family: inherit;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }
    .logbook-textarea-field::placeholder {
        color: #94A3B8;
        font-size: 13px;
        line-height: 1.5;
    }
    .logbook-textarea-field:focus {
        border-color: #2563EB !important;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15) !important;
        background-color: #FFFFFF !important;
    }
    .logbook-textarea-field.state-valid {
        border-color: #10B981 !important;
        background-color: #FFFFFF !important;
    }
    .logbook-textarea-field.state-valid:focus {
        border-color: #10B981 !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
    }
</style>
@endpush

@php
    $now = \Carbon\Carbon::now('Asia/Jakarta');
@endphp

@section('content')
<div style="min-height: 100vh; background: #F4F8FF; padding-bottom: 96px; position: relative;" x-data="checkOutApp()">

    <div style="max-width: 600px; margin: 0 auto; padding: 16px; position: relative; z-index: 10;">

        {{-- ── Top Navigation Bar: Tombol Kembali & Info Halaman ── --}}
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <a href="{{ route('dashboard') }}"
               style="display: inline-flex; align-items: center; gap: 8px; background: #FFFFFF; border: 1px solid #DBEAFE; border-radius: 14px; padding: 8px 14px; color: #1E40AF; font-size: 13px; font-weight: 700; text-decoration: none; box-shadow: 0 2px 8px rgba(37,99,235,0.06); transition: all 0.2s;"
               class="hover:border-blue-300 active:scale-95">
                <span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span>
                <span>Dashboard</span>
            </a>

            <div style="text-align: right;">
                <p style="margin: 0; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #64748B;">
                    {{ $now->translatedFormat('l, d M Y') }}
                </p>
                <p style="margin: 2px 0 0 0; font-size: 14px; font-weight: 900; color: #0F172A; letter-spacing: -0.01em;"
                   x-text="liveClock">{{ $now->format('H:i') }} WIB</p>
            </div>
        </div>

        {{-- ── Card Header: Judul & Subjudul ── --}}
        <section style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 50%, #1E40AF 100%); border-radius: 24px; padding: 22px 20px; color: #ffffff; box-shadow: 0 10px 30px rgba(37,99,235,0.22); position: relative; margin-bottom: 16px; box-sizing: border-box; overflow: hidden;">
            <div style="position: absolute; -top: 40px; -right: 40px; width: 150px; height: 150px; border-radius: 9999px; background: rgba(255,255,255,0.1); filter: blur(16px); pointer-events: none;"></div>

            <div style="position: relative; z-index: 10;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <span class="material-symbols-outlined" style="font-size: 20px; color: #93C5FD;">photo_camera_front</span>
                    <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #BFDBFE;">Absensi Wajah & Logbook</span>
                </div>
                <h1 style="margin: 0; font-size: 22px; font-weight: 900; letter-spacing: -0.01em; color: #FFFFFF;">
                    Foto Absensi Pulang
                </h1>
                <p style="margin: 6px 0 0 0; font-size: 12px; font-weight: 500; color: rgba(255,255,255,0.85); line-height: 1.45;">
                    Ambil foto wajah dan isi logbook aktivitas harian sebelum menyelesaikan absensi pulang hari ini.
                </p>
            </div>
        </section>

        {{-- ── Flash / Alert Message (Clean Amber for Warning, Blue for Success) ── --}}
        <div x-show="flashMsg"
             x-cloak
             x-transition
             :style="flashSuccess
                ? 'background: #EFF6FF; border: 1px solid #BFDBFE; color: #1E40AF;'
                : 'background: #FEF3C7; border: 1px solid #FCD34D; color: #92400E;'"
             style="border-radius: 14px; padding: 14px 16px; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 12px; width: 100%; box-sizing: border-box; box-shadow: 0 4px 12px rgba(0,0,0,0.04);">
            <div style="flex: 0 0 32px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <span class="material-symbols-outlined"
                      :style="flashSuccess ? 'color: #2563EB;' : 'color: #D97706;'"
                      style="font-size: 22px; line-height: 1; font-variation-settings: 'FILL' 1;"
                      x-text="flashSuccess ? 'check_circle' : 'warning'"></span>
            </div>
            <div style="flex: 1; min-width: 0; line-height: 1.5; overflow-wrap: anywhere; box-sizing: border-box;">
                <p style="margin: 0; font-size: 13px; font-weight: 600;" x-text="flashMsg"></p>
            </div>
        </div>

        {{-- ── Kartu Status Lokasi GPS ── --}}
        <section style="background: #FFFFFF; border: 1px solid #DBEAFE; border-radius: 20px; padding: 16px 18px; margin-bottom: 16px; box-shadow: 0 4px 16px rgba(37,99,235,0.05); box-sizing: border-box;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                    <div :style="locationVerified
                            ? 'background: #EFF6FF; border: 1px solid #BFDBFE; color: #2563EB;'
                            : 'background: #FEF3C7; border: 1px solid #FDE68A; color: #D97706;'"
                         style="width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span class="material-symbols-outlined" style="font-size: 22px;"
                              x-text="locationVerified ? 'location_on' : 'location_searching'"></span>
                    </div>
                    <div style="min-width: 0;">
                        <p style="margin: 0; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: #64748B;">
                            Status Lokasi GPS
                        </p>
                        <p style="margin: 2px 0 0 0; font-size: 13px; font-weight: 700; color: #0F172A;"
                           x-text="locationLabel">Mencari sinyal GPS...</p>
                    </div>
                </div>

                <template x-if="locationVerified">
                    <span style="background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; font-size: 10px; font-weight: 800; padding: 4px 8px; border-radius: 9999px; flex-shrink: 0;">
                        Aktif
                    </span>
                </template>
                <template x-if="!locationVerified">
                    <button type="button" @click="requestLocation"
                            style="background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 10px; cursor: pointer; flex-shrink: 0;">
                        Coba Lagi
                    </button>
                </template>
            </div>
        </section>

        {{-- ── Kartu Kamera & Preview ── --}}
        <section style="background: #FFFFFF; border: 1px solid #DBEAFE; border-radius: 24px; padding: 20px; box-shadow: 0 4px 20px rgba(37,99,235,0.06); box-sizing: border-box; margin-bottom: 20px;">

            {{-- Viewport Kamera / Preview --}}
            <div style="position: relative; width: 100%; aspect-ratio: 4/3; max-height: 420px; background: #0F172A; border-radius: 18px; overflow: hidden; display: flex; align-items: center; justify-content: center; box-shadow: inset 0 2px 8px rgba(0,0,0,0.2);">

                {{-- Video Stream Kamera --}}
                <video x-ref="videoElement"
                       playsinline
                       autoplay
                       muted
                       x-show="cameraActive && !capturedPhoto"
                       style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);">
                </video>

                {{-- Hasil Preview Foto --}}
                <img :src="capturedPhoto"
                     alt="Preview Foto Absen Pulang"
                     x-show="capturedPhoto"
                     style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);">

                {{-- Canvas Scratch untuk Capture (Tersembunyi) --}}
                <canvas x-ref="photoCanvas" style="display: none;"></canvas>

                {{-- State Belum Aktif --}}
                <div x-show="!cameraActive && !capturedPhoto"
                     style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; text-align: center; color: #FFFFFF;">
                    <div style="width: 64px; height: 64px; border-radius: 20px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <span class="material-symbols-outlined" style="font-size: 32px; color: #BFDBFE;">photo_camera_front</span>
                    </div>
                    <p style="margin: 0; font-size: 14px; font-weight: 700; color: #FFFFFF;">
                        Kamera Belum Aktif
                    </p>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: #94A3B8; max-width: 260px;">
                        Klik tombol di bawah untuk membuka kamera depan dan mengambil foto kepulangan.
                    </p>
                </div>

                {{-- Badge Status di Pojok Kamera --}}
                <div style="position: absolute; top: 12px; left: 12px; z-index: 20;">
                    <template x-if="capturedPhoto">
                        <span style="background: rgba(16,185,129,0.9); backdrop-filter: blur(8px); color: #ffffff; font-size: 10px; font-weight: 800; padding: 4px 10px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                            <span class="material-symbols-outlined" style="font-size: 14px;">check</span>
                            <span>Foto Siap</span>
                        </span>
                    </template>
                    <template x-if="cameraActive && !capturedPhoto">
                        <span style="background: rgba(37,99,235,0.85); backdrop-filter: blur(8px); color: #ffffff; font-size: 10px; font-weight: 800; padding: 4px 10px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                            <span style="width: 8px; height: 8px; border-radius: 9999px; background: #22C55E; display: inline-block;" class="animate-pulse"></span>
                            <span>Kamera Aktif</span>
                        </span>
                    </template>
                </div>

            </div>

            {{-- ── Tombol Sebelum & Saat Kamera Aktif ── --}}
            <div style="margin-top: 18px; display: flex; flex-direction: column; gap: 10px;">

                {{-- 1. Tombol Aktifkan Kamera --}}
                <button type="button"
                        x-show="!cameraActive && !capturedPhoto"
                        @click="startCamera"
                        :disabled="cameraLoading"
                        style="width: 100%; height: 48px; border-radius: 16px; background: #2563EB; color: #FFFFFF; font-size: 14px; font-weight: 800; border: none; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; box-shadow: 0 4px 14px rgba(37,99,235,0.3); transition: all 0.2s;"
                        class="hover:bg-blue-700 active:scale-98 disabled:opacity-50">
                    <span class="material-symbols-outlined" style="font-size: 20px;" x-text="cameraLoading ? 'hourglass_top' : 'videocam'">videocam</span>
                    <span x-text="cameraLoading ? 'Mengakses Kamera...' : 'Aktifkan Kamera'">Aktifkan Kamera</span>
                </button>

                {{-- 2. Tombol Ambil Foto (Ketika Kamera Sedang Aktif) --}}
                <button type="button"
                        x-show="cameraActive && !capturedPhoto"
                        @click="takeSnapshot"
                        style="width: 100%; height: 48px; border-radius: 16px; background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); color: #FFFFFF; font-size: 14px; font-weight: 800; border: none; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; box-shadow: 0 6px 18px rgba(37,99,235,0.35); transition: all 0.2s;"
                        class="hover:bg-blue-700 active:scale-98">
                    <span class="material-symbols-outlined" style="font-size: 22px;">photo_camera</span>
                    <span>Ambil Foto</span>
                </button>

                {{-- ── Bagian Setelah Foto Berhasil Diambil: Logbook & Tombol Aksi ── --}}
                <div x-show="capturedPhoto" x-cloak style="display: flex; flex-direction: column; gap: 16px; width: 100%;">

                    {{-- Form Logbook Aktivitas Harian (Wajib minimal 20 karakter) --}}
                    <div style="width: 100%; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 20px; padding: 18px 18px; box-sizing: border-box;">
                        {{-- 1. Baris Label & Counter --}}
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
                            <label for="logbook-textarea" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 800; color: #0F172A; cursor: pointer;">
                                <span class="material-symbols-outlined" style="font-size: 20px; color: #2563EB;">edit_note</span>
                                <span>Logbook Aktivitas Harian</span>
                                <span style="color: #EF4444; font-size: 14px; font-weight: 900;">*</span>
                            </label>

                            <span :style="logbookTrimmedLength >= 20 ? 'background: #DCFCE7; color: #15803D; border-color: #86EFAC;' : 'background: #F1F5F9; color: #475569; border-color: #CBD5E1;'"
                                  style="font-size: 11.5px; font-weight: 800; padding: 3px 10px; border-radius: 9999px; border-width: 1px; border-style: solid; transition: all 0.2s;"
                                  x-text="`${logbookTrimmedLength} karakter · minimal 20`">
                                0 karakter · minimal 20
                            </span>
                        </div>

                        {{-- 2. Teks Petunjuk Tepat di Bawah Label --}}
                        <p style="margin: 0 0 12px 0; font-size: 12.5px; color: #475569; line-height: 1.55;">
                            Tuliskan aktivitas atau tugas yang dikerjakan, hal yang dipelajari, serta hasil pekerjaan atau kemajuan hari ini (minimal 20 karakter).
                        </p>

                        {{-- 3. Textarea Memenuhi Lebar Kontainer (100%), Tinggi 120-140px, Nyaman, Focus Ring Halus --}}
                        <textarea id="logbook-textarea"
                                  x-model="logbook"
                                  placeholder="Contoh: Mempelajari pengolahan data absensi, menguji validasi GPS, dan memahami alur penyimpanan data ke database."
                                  :class="logbookTrimmedLength >= 20 ? 'state-valid' : ''"
                                  class="logbook-textarea-field"></textarea>

                        {{-- 4. Status Validasi di Bawah Textarea (Netral sebelum 20 karakter, Sukses halus setelah mencapai 20 karakter) --}}
                        <div style="margin-top: 10px; min-height: 22px; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                            <div>
                                <template x-if="logbookTrimmedLength === 0">
                                    <span style="font-size: 12px; color: #64748B; display: inline-flex; align-items: center; gap: 6px;">
                                        <span class="material-symbols-outlined" style="font-size: 16px; color: #94A3B8;">info</span>
                                        <span>Tuliskan minimal 20 karakter.</span>
                                    </span>
                                </template>
                                <template x-if="logbookTrimmedLength > 0 && logbookTrimmedLength < 20">
                                    <span style="font-size: 12px; color: #475569; display: inline-flex; align-items: center; gap: 6px;">
                                        <span class="material-symbols-outlined" style="font-size: 16px; color: #64748B;">edit_note</span>
                                        <span>Tersisa <strong style="color: #1E40AF;" x-text="20 - logbookTrimmedLength"></strong> karakter lagi untuk mencapai batas minimal.</span>
                                    </span>
                                </template>
                                <template x-if="logbookTrimmedLength >= 20">
                                    <span style="font-size: 12px; color: #15803D; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                                        <span class="material-symbols-outlined" style="font-size: 16px; color: #16A34A;">check_circle</span>
                                        <span>Logbook telah memenuhi syarat minimal penulisan.</span>
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- ── Kontainer Tombol Aksi (Jarak Vertikal Konsisten 14px & Lebar Penuh) ── --}}
                    <div style="display: flex; flex-direction: column; gap: 14px; width: 100%;">

                        {{-- Tombol Ambil Ulang Foto --}}
                        <button type="button"
                                @click="retakePhoto"
                                :disabled="submitting"
                                style="width: 100%; height: 46px; border-radius: 16px; background: #F1F5F9; color: #334155; font-size: 13.5px; font-weight: 700; border: 1px solid #CBD5E1; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: all 0.2s;"
                                class="hover:bg-slate-200 active:scale-98 disabled:opacity-50">
                            <span class="material-symbols-outlined" style="font-size: 18px;">replay</span>
                            <span>Ambil Ulang Foto</span>
                        </button>

                        {{-- Tombol Kirim Absensi Pulang --}}
                        <button type="button"
                                @click="submitAbsen"
                                :disabled="submitting || !locationVerified || !photoBlob || logbookTrimmedLength < 20"
                                style="width: 100%; height: 52px; border-radius: 16px; background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); color: #FFFFFF; font-size: 15px; font-weight: 900; letter-spacing: 0.02em; border: none; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; box-shadow: 0 8px 24px rgba(37,99,235,0.38); transition: all 0.2s;"
                                class="hover:bg-blue-700 active:scale-98 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="material-symbols-outlined" style="font-size: 22px;"
                                  x-text="submitting ? 'hourglass_top' : 'send'">send</span>
                            <span x-text="submitting ? 'Memproses Absensi Pulang...' : 'Kirim Absensi Pulang'">Kirim Absensi Pulang</span>
                        </button>
                    </div>
                </div>

            </div>

        </section>

    </div>

</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="dashboard" role="siswa" />
@endsection

@push('scripts')
<script>
function checkOutApp() {
    return {
        // State Geolocation
        latitude:         null,
        longitude:        null,
        locationVerified: false,
        locationLabel:    'Mencari sinyal GPS...',

        // State Kamera
        mediaStream:      null,
        cameraActive:     false,
        cameraLoading:    false,
        capturedPhoto:    null,
        photoBlob:        null,

        // State Logbook
        logbook:          '',

        // State Jam & Submission
        liveClock:        '{{ $now->format("H:i:s") }}',
        clockTimer:       null,
        submitting:       false,
        flashMsg:         '',
        flashSuccess:     false,

        get logbookTrimmedLength() {
            return (this.logbook || '').trim().length;
        },

        init() {
            // Jam digital berjalan
            this.clockTimer = setInterval(() => {
                const d = new Date();
                const pad = (n) => String(n).padStart(2, '0');
                this.liveClock = `${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())} WIB`;
            }, 1000);

            // Inisialisasi lokasi
            this.requestLocation();

            // Tangani saat tab/halaman ditutup atau navigasi keluar
            window.addEventListener('beforeunload', () => {
                this.stopCamera();
            });
        },

        requestLocation() {
            if (!navigator.geolocation) {
                this.locationVerified = false;
                this.locationLabel    = 'GPS tidak didukung browser ini.';
                this.showFlash('Browser Anda tidak mendukung fitur Geolocation.', false);
                return;
            }

            this.locationLabel = 'Mendapatkan koordinat presisi...';
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.latitude         = pos.coords.latitude;
                    this.longitude        = pos.coords.longitude;
                    this.locationVerified = true;
                    this.locationLabel    = `Lokasi terverifikasi (${this.latitude.toFixed(5)}, ${this.longitude.toFixed(5)})`;
                },
                (err) => {
                    this.locationVerified = false;
                    let msg = 'Izin lokasi GPS ditolak atau tidak tersedia.';
                    if (err.code === 1) msg = 'Akses lokasi ditolak. Silakan izinkan GPS di browser.';
                    else if (err.code === 2) msg = 'Sinyal lokasi tidak dapat diperoleh.';
                    else if (err.code === 3) msg = 'Waktu permintaan lokasi habis.';
                    this.locationLabel = msg;
                    this.showFlash(msg, false);
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        },

        async startCamera() {
            this.cameraLoading = true;
            this.flashMsg = '';

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                this.cameraLoading = false;
                this.showFlash('Perangkat atau browser Anda tidak mendukung akses kamera.', false);
                return;
            }

            try {
                // Prioritaskan kamera depan (facingMode: 'user')
                const constraints = {
                    video: {
                        facingMode: { ideal: 'user' },
                        width:      { ideal: 1280 },
                        height:     { ideal: 960 },
                    },
                    audio: false
                };

                this.mediaStream = await navigator.mediaDevices.getUserMedia(constraints);
                const videoEl = this.$refs.videoElement;
                if (videoEl) {
                    videoEl.srcObject = this.mediaStream;
                    await videoEl.play();
                }
                this.cameraActive = true;
            } catch (err) {
                let msg = 'Gagal mengakses kamera.';
                if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                    msg = 'Izin kamera ditolak. Mohon izinkan akses kamera di pengaturan browser.';
                } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                    msg = 'Kamera tidak ditemukan pada perangkat ini.';
                } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
                    msg = 'Kamera sedang digunakan oleh aplikasi lain.';
                }
                this.showFlash(msg, false);
                this.cameraActive = false;
            } finally {
                this.cameraLoading = false;
            }
        },

        takeSnapshot() {
            const videoEl  = this.$refs.videoElement;
            const canvasEl = this.$refs.photoCanvas;

            if (!videoEl || !canvasEl) return;

            const width  = videoEl.videoWidth || 640;
            const height = videoEl.videoHeight || 480;

            canvasEl.width  = width;
            canvasEl.height = height;

            const ctx = canvasEl.getContext('2d');
            // Gambar frame video ke canvas
            ctx.drawImage(videoEl, 0, 0, width, height);

            // Konversi ke image Blob (image/jpeg)
            canvasEl.toBlob((blob) => {
                if (!blob) {
                    this.showFlash('Gagal memproses hasil foto. Coba lagi.', false);
                    return;
                }
                this.photoBlob = blob;
                this.capturedPhoto = URL.createObjectURL(blob);
                // Matikan camera stream setelah berhasil mengambil foto untuk hemat baterai
                this.stopCamera();
            }, 'image/jpeg', 0.9);
        },

        retakePhoto() {
            if (this.capturedPhoto) {
                URL.revokeObjectURL(this.capturedPhoto);
                this.capturedPhoto = null;
                this.photoBlob     = null;
            }
            this.startCamera();
        },

        stopCamera() {
            if (this.mediaStream) {
                this.mediaStream.getTracks().forEach((track) => track.stop());
                this.mediaStream = null;
            }
            this.cameraActive = false;
        },

        async submitAbsen() {
            if (!this.locationVerified || this.latitude === null || this.longitude === null) {
                this.showFlash('Lokasi GPS belum tersedia. Silakan klik Coba Lagi pada status lokasi.', false);
                return;
            }

            if (!this.photoBlob) {
                this.showFlash('Foto absensi pulang wajib diambil terlebih dahulu.', false);
                return;
            }

            if (this.logbookTrimmedLength < 20) {
                this.showFlash('Logbook aktivitas harian wajib diisi minimal 20 karakter.', false);
                return;
            }

            this.submitting = true;
            this.flashMsg   = '';

            try {
                const formData = new FormData();
                formData.append('type', 'pulang');
                formData.append('latitude', this.latitude);
                formData.append('longitude', this.longitude);
                formData.append('foto', this.photoBlob, 'absen-pulang.jpg');
                formData.append('logbook', this.logbook.trim());

                const res = await fetch('{{ route('attendance.store') }}', {
                    method:  'POST',
                    headers: {
                        'Accept':       'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData,
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    this.showFlash(data.message, true);
                    this.stopCamera();
                    // Alihkan kembali ke dashboard setelah sukses
                    setTimeout(() => {
                        window.location.href = '{{ route('dashboard') }}';
                    }, 1200);
                } else {
                    let errorMsg = data.message || 'Gagal memproses absensi pulang.';
                    if (data.errors) {
                        const firstError = Object.values(data.errors).flat()[0];
                        if (firstError) errorMsg = firstError;
                    }
                    this.showFlash(errorMsg, false);
                }
            } catch (err) {
                this.showFlash('Terjadi kesalahan jaringan atau server. Silakan coba lagi.', false);
            } finally {
                this.submitting = false;
            }
        },

        showFlash(msg, success) {
            this.flashMsg     = msg;
            this.flashSuccess = success;
        },
    };
}
</script>
@endpush
