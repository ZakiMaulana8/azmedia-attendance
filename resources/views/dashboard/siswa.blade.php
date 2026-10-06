@extends('layouts.app')
@section('title', 'Dashboard')

@php
    // ── Logika waktu (Asia/Jakarta) ──────────────────────────────────
    $now  = \Carbon\Carbon::now('Asia/Jakarta');
    $hour = $now->hour;

    if ($hour >= 5 && $hour < 11) {
        $greeting = 'Selamat Pagi';
        $greetIcon = '☀️';
    } elseif ($hour >= 11 && $hour < 14) {
        $greeting = 'Selamat Siang';
        $greetIcon = '🌤️';
    } elseif ($hour >= 14 && $hour < 18) {
        $greeting = 'Selamat Sore';
        $greetIcon = '🌇';
    } else {
        $greeting = 'Selamat Malam';
        $greetIcon = '🌙';
    }

    // ── Quote motivasi berputar per hari ─────────────────────────────
    $quotes = [
        'Disiplin hari ini adalah investasi masa depanmu.',
        'Konsistensi kecil membangun pencapaian besar.',
        'Hadir tepat waktu adalah bentuk profesionalisme.',
        'Setiap hari adalah kesempatan untuk menjadi lebih baik.',
        'Kerja keras yang tulus tidak pernah sia-sia.',
        'Mulai dengan semangat, selesaikan dengan bangga.',
        'Komitmen membedakan yang biasa dan yang luar biasa.',
    ];
    $quote = $quotes[$now->dayOfWeek % count($quotes)];
@endphp

@section('content')
<div style="min-height: 100vh; background: #F4F8FF; padding-bottom: 80px; position: relative;" x-data="attendanceApp()">

    {{-- Watermark logo A halus di background --}}
    <div style="position: absolute; top: -10px; left: -10px; width: 220px; height: 220px; opacity: 0.025; color: #2563EB; pointer-events: none; z-index: 0;">
        <svg viewBox="0 0 100 100" fill="currentColor">
            <path d="M50 5 L95 90 L70 90 L50 45 L30 90 L5 90 Z"/>
        </svg>
    </div>

    <div style="max-width: 672px; margin: 0 auto; padding: 24px 16px 16px 16px; position: relative; z-index: 10;">

        {{-- ════════════════════════════════════════════════════════════
             1. GREETING CARD — SOLID BLUE GRADIENT (KONTRAS HIGH & RESPONSIVE)
        ════════════════════════════════════════════════════════════ --}}
        <section style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 50%, #1E40AF 100%); border-radius: 24px; padding: 26px 24px; color: #ffffff; box-shadow: 0 10px 30px rgba(37,99,235,0.25); position: relative; margin-top: 4px; margin-bottom: 24px; width: 100%; box-sizing: border-box;">

            {{-- Ornamen Lingkaran Transparan (overflow hanya pada ornamen, bukan seluruh section) --}}
            <div style="position: absolute; inset: 0; border-radius: 24px; overflow: hidden; pointer-events: none; z-index: 0;">
                <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; border-radius: 9999px; background: rgba(255,255,255,0.1); filter: blur(20px);"></div>
                <div style="position: absolute; bottom: -40px; left: -40px; width: 160px; height: 160px; border-radius: 9999px; background: rgba(59,130,246,0.2); filter: blur(16px);"></div>
            </div>

            <div style="position: relative; z-index: 10; width: 100%;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: nowrap; width: 100%;">

                    {{-- Kiri: Salam, Nama & Quote (Tampil Lengkap & Tidak Terpotong) --}}
                    <div style="flex: 1 1 auto; min-width: 0;">
                        <p style="color: rgba(255,255,255,0.9); font-size: 13px; font-weight: 600; margin: 0 0 6px 0; display: flex; align-items: center; gap: 6px;">
                            <span>{{ $greetIcon }}</span> <span>{{ $greeting }}</span>
                        </p>
                        <h2 style="color: #ffffff; font-size: 22px; font-weight: 800; line-height: 1.35; letter-spacing: -0.01em; margin: 0; word-break: break-word; overflow-wrap: break-word; white-space: normal; text-overflow: clip; overflow: visible;">
                            Halo, {{ $user->name }}!
                        </h2>
                        <p style="color: rgba(255,255,255,0.85); font-size: 12px; font-weight: 500; margin-top: 6px; margin-bottom: 0; line-height: 1.5; word-break: break-word; overflow-wrap: break-word; white-space: normal;">
                            {{ $quote }}
                        </p>
                    </div>

                    {{-- Kanan: Card Tanggal Glassmorphism (Ukuran Utuh, Tidak Boleh Terpotong) --}}
                    <div style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.38); backdrop-filter: blur(10px); border-radius: 16px; padding: 10px 14px; text-align: center; min-width: 80px; flex-shrink: 0; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: flex; flex-direction: column; align-items: center; justify-content: center; box-sizing: border-box;">
                        <p style="color: rgba(255,255,255,0.9); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; line-height: 1.2;">
                            {{ $now->translatedFormat('D') }}
                        </p>
                        <p style="color: #ffffff; font-size: 28px; font-weight: 900; line-height: 1.1; letter-spacing: -0.02em; margin: 2px 0;">
                            {{ $now->format('d') }}
                        </p>
                        <p style="color: rgba(255,255,255,0.95); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; margin: 0; line-height: 1.2; white-space: nowrap;">
                            {{ $now->translatedFormat('M Y') }}
                        </p>
                    </div>
                </div>

                {{-- Alert Pesan Lokasi / Flash Notification (Modern Soft Red Alert, Dynamic Height & Full Wrapping) --}}
                @if(session('error'))
                <div style="background: #FEF2F2; border: 1px solid #FCA5A5; color: #991B1B; box-shadow: 0 4px 14px rgba(220,38,38,0.12); margin-top: 16px; display: flex; align-items: flex-start; gap: 12px; border-radius: 16px; padding: 14px 16px; font-size: 13px; font-weight: 600; line-height: 1.5; width: 100%; box-sizing: border-box; height: auto;">
                    <span class="material-symbols-outlined" style="color: #DC2626; font-size: 22px; flex-shrink: 0; margin-top: 1px;">error</span>
                    <span style="flex: 1; word-break: break-word; white-space: normal; height: auto;">{{ session('error') }}</span>
                </div>
                @endif

                @if(session('success'))
                <div style="background: #FFFFFF; border: 1px solid #93C5FD; color: #1E40AF; box-shadow: 0 4px 14px rgba(37,99,235,0.12); margin-top: 16px; display: flex; align-items: flex-start; gap: 12px; border-radius: 16px; padding: 14px 16px; font-size: 13px; font-weight: 600; line-height: 1.5; width: 100%; box-sizing: border-box; height: auto;">
                    <span class="material-symbols-outlined" style="color: #2563EB; font-size: 22px; flex-shrink: 0; margin-top: 1px;">check_circle</span>
                    <span style="flex: 1; word-break: break-word; white-space: normal; height: auto;">{{ session('success') }}</span>
                </div>
                @endif

                <div x-show="flashMsg"
                     x-transition
                     :style="flashSuccess
                        ? 'background: #FFFFFF; border: 1px solid #93C5FD; color: #1E40AF; box-shadow: 0 4px 14px rgba(37,99,235,0.12);'
                        : 'background: #FEF2F2; border: 1px solid #FCA5A5; color: #991B1B; box-shadow: 0 4px 14px rgba(220,38,38,0.12);'"
                     style="margin-top: 16px; display: flex; align-items: flex-start; gap: 12px; border-radius: 16px; padding: 14px 16px; font-size: 13px; font-weight: 600; line-height: 1.5; width: 100%; box-sizing: border-box; height: auto;">
                    <span class="material-symbols-outlined"
                          :style="flashSuccess ? 'color: #2563EB;' : 'color: #DC2626;'"
                          style="font-size: 22px; flex-shrink: 0; margin-top: 1px;"
                          x-text="flashSuccess ? 'check_circle' : 'error'"></span>
                    <span x-text="flashMsg" style="flex: 1; word-break: break-word; white-space: normal; height: auto; display: block;"></span>
                </div>
            </div>
        </section>

        {{-- ════════════════════════════════════════════════════════════
             2. CARD JAM MASUK & JAM PULANG
        ════════════════════════════════════════════════════════════ --}}
        <section style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; margin-bottom: 24px;">

            {{-- Jam Masuk --}}
            <div style="background: #FFFFFF; border: 1px solid #DBEAFE; border-radius: 20px; padding: 16px; box-shadow: 0 4px 16px rgba(37,99,235,0.06); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span class="material-symbols-outlined" style="color: #2563EB; font-size: 18px;">login</span>
                            <span style="color: #0F172A; font-weight: 800; font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em;">JAM MASUK</span>
                        </div>
                        <span style="background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; font-weight: 800; font-size: 9px; padding: 2px 8px; border-radius: 9999px;">Fleksibel</span>
                    </div>

                    <p style="font-weight: 900; font-size: 24px; letter-spacing: -0.02em; margin-top: 4px;"
                       :style="jamMasuk ? 'color: #0F172A;' : 'color: #0F172A;'"
                       x-text="jamMasuk || '--:--'">--:--</p>
                </div>

                <div style="margin-top: 12px;">
                    <template x-if="jamMasuk">
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; font-weight: 700; font-size: 11px; padding: 3px 10px; border-radius: 9999px;">
                            <span class="material-symbols-outlined" style="font-size: 14px;">check_circle</span>
                            <span>Hadir</span>
                        </span>
                    </template>
                    <template x-if="!jamMasuk">
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #F0F7FF; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; font-size: 11px; padding: 3px 10px; border-radius: 9999px;">
                            <span class="material-symbols-outlined" style="font-size: 14px; color: #64748B;">hourglass_empty</span>
                            <span>Belum Absen</span>
                        </span>
                    </template>
                </div>
            </div>

            {{-- Jam Pulang --}}
            <div style="background: #FFFFFF; border: 1px solid #DBEAFE; border-radius: 20px; padding: 16px; box-shadow: 0 4px 16px rgba(37,99,235,0.06); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span class="material-symbols-outlined" style="color: #2563EB; font-size: 18px;">logout</span>
                            <span style="color: #0F172A; font-weight: 800; font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em;">JAM PULANG</span>
                        </div>
                        <span style="background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; font-weight: 800; font-size: 9px; padding: 2px 8px; border-radius: 9999px;">Fleksibel</span>
                    </div>

                    <p style="font-weight: 900; font-size: 24px; letter-spacing: -0.02em; margin-top: 4px;"
                       :style="jamPulang ? 'color: #0F172A;' : 'color: #0F172A;'"
                       x-text="jamPulang || '--:--'">--:--</p>
                </div>

                <div style="margin-top: 12px;">
                    <template x-if="jamPulang">
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; font-weight: 700; font-size: 11px; padding: 3px 10px; border-radius: 9999px;">
                            <span class="material-symbols-outlined" style="font-size: 14px;">task_alt</span>
                            <span>Sudah Pulang</span>
                        </span>
                    </template>
                    <template x-if="!jamPulang">
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #F0F7FF; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; font-size: 11px; padding: 3px 10px; border-radius: 9999px;">
                            <span class="material-symbols-outlined" style="font-size: 14px; color: #64748B;">hourglass_empty</span>
                            <span>Belum Absen</span>
                        </span>
                    </template>
                </div>
            </div>

        </section>

        {{-- ════════════════════════════════════════════════════════════
             3. AREA ABSENSI & TOMBOL ABSEN (BIRU KUAT #3B82F6 → #2563EB)
        ════════════════════════════════════════════════════════════ --}}
        <section style="background: #EEF5FF; border: 1px solid #DBEAFE; border-radius: 24px; padding: 24px 16px; display: flex; flex-direction: column; align-items: center; margin-bottom: 24px;">

            {{-- Lingkaran Efek Pulse & Glow --}}
            <div style="position: relative; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                <div style="position: absolute; width: 176px; height: 176px; border-radius: 9999px; border: 2px solid rgba(37,99,235,0.2);" class="animate-ping"></div>

                {{-- Tombol Utama Absensi --}}
                <button @click="doAbsen"
                        :disabled="loading || absenDone"
                        style="position: relative; width: 144px; height: 144px; border-radius: 28px; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); color: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: center; border: none; box-shadow: 0 12px 32px rgba(37,99,235,0.45); cursor: pointer; transition: all 0.2s;"
                        class="active:scale-95 disabled:opacity-50">

                    <span class="material-symbols-outlined" style="font-size: 52px; color: #ffffff; margin-bottom: 4px;"
                          x-text="loading ? 'hourglass_top' : 'fingerprint'">fingerprint</span>

                    <span style="color: #ffffff; font-weight: 800; font-size: 12px; letter-spacing: 0.08em; text-transform: uppercase;"
                          x-text="buttonLabel">
                        @if($today?->jam_masuk && !$today?->jam_pulang)
                            ABSEN PULANG
                        @elseif(!$today?->jam_masuk)
                            ABSEN MASUK
                        @else
                            SELESAI
                        @endif
                    </span>
                </button>
            </div>

            {{-- Indikator Status Lokasi --}}
            <div style="background: #FFFFFF; border: 1px solid #2563EB; border-radius: 9999px; padding: 8px 16px; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(37,99,235,0.1);">
                <span class="material-symbols-outlined" style="color: #2563EB; font-size: 16px;">location_on</span>
                <p style="color: #2563EB; font-weight: 700; font-size: 12px; margin: 0;" x-text="locationLabel">📍 Mendapatkan lokasi...</p>
            </div>

            <p style="color: #64748B; font-weight: 500; font-size: 11px; margin-top: 8px; margin-bottom: 0;">
                {{ $user->asal_sekolah ?? 'Azmedia Office' }}
            </p>
        </section>

        {{-- ════════════════════════════════════════════════════════════
             4. LAYANAN CEPAT (QUICK SERVICES)
        ════════════════════════════════════════════════════════════ --}}
        <section style="margin-bottom: 24px;">
            <h3 style="color: #475569; font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 12px; padding-left: 4px;">Layanan Cepat</h3>

            <div style="display: flex; flex-direction: column; gap: 10px;">

                {{-- Pengajuan Izin --}}
                <a href="{{ route('permissions.create') }}"
                   style="background: #FFFFFF; border: 1px solid #DBEAFE; border-radius: 20px; padding: 14px; box-shadow: 0 2px 10px rgba(37,99,235,0.04); display: flex; align-items: center; gap: 14px; text-decoration: none;"
                   class="hover:border-blue-200 transition-all">
                    <div style="width: 42px; height: 42px; border-radius: 14px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">event_busy</span>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <p style="color: #0F172A; font-weight: 700; font-size: 14px; margin: 0;">Pengajuan Izin</p>
                        <p style="color: #64748B; font-size: 12px; margin: 2px 0 0 0;">Ajukan dan kelola izin absensi</p>
                    </div>
                    <span class="material-symbols-outlined" style="color: #94A3B8; font-size: 18px;">chevron_right</span>
                </a>

                {{-- Laporan Kehadiran --}}
                <a href="{{ route('reports.index') }}"
                   style="background: #FFFFFF; border: 1px solid #DBEAFE; border-radius: 20px; padding: 14px; box-shadow: 0 2px 10px rgba(37,99,235,0.04); display: flex; align-items: center; gap: 14px; text-decoration: none;"
                   class="hover:border-blue-200 transition-all">
                    <div style="width: 42px; height: 42px; border-radius: 14px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">bar_chart</span>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <p style="color: #0F172A; font-weight: 700; font-size: 14px; margin: 0;">Laporan Kehadiran</p>
                        <p style="color: #64748B; font-size: 12px; margin: 2px 0 0 0;">Riwayat dan rekap absensi</p>
                    </div>
                    <span class="material-symbols-outlined" style="color: #94A3B8; font-size: 18px;">chevron_right</span>
                </a>

                {{-- Laporan Kegiatan --}}
                <a href="{{ route('activity-reports.index') }}"
                   style="background: #FFFFFF; border: 1px solid #DBEAFE; border-radius: 20px; padding: 14px; box-shadow: 0 2px 10px rgba(37,99,235,0.04); display: flex; align-items: center; gap: 14px; text-decoration: none;"
                   class="hover:border-blue-200 transition-all">
                    <div style="width: 42px; height: 42px; border-radius: 14px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">photo_library</span>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <p style="color: #0F172A; font-weight: 700; font-size: 14px; margin: 0;">Laporan Kegiatan</p>
                        <p style="color: #64748B; font-size: 12px; margin: 2px 0 0 0;">Upload dan kelola dokumentasi kegiatan</p>
                    </div>
                    <span class="material-symbols-outlined" style="color: #94A3B8; font-size: 18px;">chevron_right</span>
                </a>

            </div>
        </section>

        {{-- ════════════════════════════════════════════════════════════
             5. INFORMASI MAGANG
        ════════════════════════════════════════════════════════════ --}}
        @if($user->tanggal_mulai && $user->tanggal_selesai)
        @php $progress = $user->progressMagang(); $sisa = $user->sisaHariMagang(); @endphp
        <section>
            <div style="background: #FFFFFF; border: 1px solid #DBEAFE; border-radius: 20px; padding: 16px; box-shadow: 0 4px 16px rgba(37,99,235,0.05);">

                {{-- Header --}}
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                    <div style="width: 32px; height: 32px; border-radius: 10px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="font-size: 18px;">business_center</span>
                    </div>
                    <p style="color: #0F172A; font-weight: 800; font-size: 15px; margin: 0;">Informasi Magang</p>
                </div>

                {{-- Grid Detail --}}
                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; margin-bottom: 14px;">
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 10px;">
                        <p style="color: #64748B; font-weight: 800; font-size: 10px; text-transform: uppercase; margin-bottom: 2px;">Asal Sekolah</p>
                        <p style="color: #0F172A; font-weight: 700; font-size: 13px; margin: 0;">{{ $user->asal_sekolah ?? 'SMKN 1 BOYOLANGU' }}</p>
                    </div>
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 10px;">
                        <p style="color: #64748B; font-weight: 800; font-size: 10px; text-transform: uppercase; margin-bottom: 2px;">Jurusan</p>
                        <p style="color: #0F172A; font-weight: 700; font-size: 13px; margin: 0;">{{ $user->jurusan ?? 'RPL' }}</p>
                    </div>
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 10px;">
                        <p style="color: #64748B; font-weight: 800; font-size: 10px; text-transform: uppercase; margin-bottom: 2px;">Tanggal Mulai</p>
                        <p style="color: #0F172A; font-weight: 700; font-size: 13px; margin: 0;">{{ $user->tanggal_mulai->translatedFormat('d M Y') }}</p>
                    </div>
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 10px;">
                        <p style="color: #64748B; font-weight: 800; font-size: 10px; text-transform: uppercase; margin-bottom: 2px;">Tanggal Selesai</p>
                        <p style="color: #0F172A; font-weight: 700; font-size: 13px; margin: 0;">{{ $user->tanggal_selesai->translatedFormat('d M Y') }}</p>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="color: #475569; font-size: 11px; font-weight: 600;">Progress Magang</span>
                        <span style="color: #2563EB; font-size: 13px; font-weight: 900;">{{ $progress }}%</span>
                    </div>
                    <div style="width: 100%; background: #E2E8F0; border-radius: 9999px; height: 8px; overflow: hidden;">
                        <div style="background: linear-gradient(90deg, #2563EB 0%, #3B82F6 100%); height: 8px; border-radius: 9999px; width: {{ $progress }}%;"></div>
                    </div>
                    @if($sisa !== null)
                    <p style="color: #64748B; font-size: 11px; margin-top: 6px; font-weight: 500;">
                        {{ $sisa > 0 ? $sisa . ' hari tersisa' : 'Periode magang telah selesai' }}
                    </p>
                    @endif
                </div>

            </div>
        </section>
        @endif

    </div>

</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="dashboard" role="siswa" />
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function attendanceApp() {
    return {
        loading:          false,
        locationVerified: false,
        locationLabel:    'Mendapatkan lokasi...',
        flashMsg:         '',
        flashSuccess:     true,
        latitude:         null,
        longitude:        null,

        jamMasuk:    @json($today?->jam_masuk  ? \Carbon\Carbon::parse($today->jam_masuk)->format('H:i')  : null),
        jamPulang:   @json($today?->jam_pulang ? \Carbon\Carbon::parse($today->jam_pulang)->format('H:i') : null),
        statusMasuk: @json($today?->status ?? 'hadir'),
        keterangan:  @json($today?->keterangan),

        absenDone:   {{ ($today?->jam_masuk && $today?->jam_pulang) ? 'true' : 'false' }},
        buttonLabel: '{{ $today?->jam_masuk && !$today?->jam_pulang ? "Absen Pulang" : (!$today?->jam_masuk ? "Absen Masuk" : "Selesai") }}',

        init() {
            if (!navigator.geolocation) return;
            navigator.geolocation.watchPosition(
                (pos) => {
                    this.latitude         = pos.coords.latitude;
                    this.longitude        = pos.coords.longitude;
                    this.locationVerified = true;
                    this.locationLabel    = 'Lokasi terverifikasi ✓';
                },
                () => {
                    this.locationVerified = false;
                    this.locationLabel    = 'Lokasi tidak tersedia';
                },
                { enableHighAccuracy: true }
            );
        },

        async doAbsen() {
            if (!this.locationVerified) {
                this.showFlash('Izinkan akses lokasi GPS Anda untuk melakukan absensi.', false);
                return;
            }

            const type = this.buttonLabel === 'Absen Pulang' ? 'pulang' : 'masuk';

            if (type === 'masuk') {
                await this.executeAbsen('masuk');
                return;
            }

            // Modal input logbook kegiatan harian (wajib diisi)
            const { value: logbook, isConfirmed } = await Swal.fire({
                title: 'Logbook Kegiatan Hari Ini',
                text: 'Tuliskan ringkasan kegiatan atau tugas yang Anda selesaikan hari ini sebelum check-out.',
                input: 'textarea',
                inputPlaceholder: 'Contoh: Menyelesaikan slicing halaman dashboard dan testing respon API...',
                inputAttributes: {
                    'rows': 4,
                    'maxlength': 1000,
                },
                showCancelButton: true,
                confirmButtonColor: '#2563EB',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Simpan & Check-out',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                inputValidator: (value) => {
                    if (!value || !value.trim()) {
                        return 'Logbook kegiatan wajib diisi!';
                    }
                }
            });

            if (!isConfirmed || !logbook || !logbook.trim()) {
                return;
            }

            await this.executeAbsen('pulang', logbook.trim());
        },

        async executeAbsen(type, logbook = null) {
            this.loading = true;
            try {
                const payload = {
                    type,
                    latitude:  this.latitude,
                    longitude: this.longitude,
                };

                if (type === 'pulang') {
                    payload.logbook = logbook;
                }

                const res  = await fetch('{{ route('attendance.store') }}', {
                    method:  'POST',
                    headers: {
                        'Content-Type':  'application/json',
                        'Accept':        'application/json',
                        'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();

                if (res.ok && data.success) {
                    this.showFlash(data.message, true);
                    if (type === 'masuk') {
                        this.jamMasuk    = data.jam_masuk;
                        this.statusMasuk = data.status;
                        this.buttonLabel = 'Absen Pulang';
                    } else {
                        this.jamPulang   = data.jam_pulang;
                        this.keterangan  = data.keterangan;
                        this.buttonLabel = 'Selesai';
                        this.absenDone   = true;
                    }
                } else {
                    let errorMsg = data.message || 'Gagal memproses absensi.';
                    if (data.errors) {
                        const firstError = Object.values(data.errors).flat()[0];
                        if (firstError) errorMsg = firstError;
                    }
                    this.showFlash(errorMsg, false);
                }
            } catch {
                this.showFlash('Terjadi kesalahan jaringan atau server. Coba lagi.', false);
            } finally {
                this.loading = false;
            }
        },

        showFlash(msg, success) {
            this.flashMsg     = msg;
            this.flashSuccess = success;
            setTimeout(() => this.flashMsg = '', 4000);
        },
    };
}
</script>
@endpush
