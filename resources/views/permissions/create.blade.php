@extends('layouts.app')
@section('title', 'Ajukan Izin')

@section('content')
<div class="px-md max-w-screen-xl mx-auto pt-lg pb-4">

    {{-- Page title --}}
    <section class="mb-lg">
        <h2 class="text-headline-lg-mobile font-bold text-on-surface">Form Izin Siswa</h2>
        <p class="text-body-sm text-on-surface-variant">
            Silakan lengkapi data izin Anda untuk proses administrasi.
        </p>
    </section>

    @if(session('success'))
        <div class="mb-lg p-md bg-primary-container text-on-primary-container rounded-xl flex items-center gap-sm">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg">

        {{-- ── Form ─────────────────────────────────────────────── --}}
        <section class="lg:col-span-7">
            <div class="bg-surface-container-lowest rounded-xl p-md shadow-sm border border-outline-variant">
                <form method="POST" action="{{ route('permissions.store') }}"
                      enctype="multipart/form-data"
                      class="space-y-md"
                      x-data="permissionForm()">
                    @csrf

                    {{-- Jenis Izin --}}
                    <div class="space-y-xs">
                        <label for="jenis" class="text-label-md text-on-surface-variant block">
                            Jenis Izin
                        </label>
                        <div class="relative">
                            <select id="jenis" name="jenis"
                                    class="w-full h-touch-target px-md rounded-lg border border-outline-variant
                                           bg-surface focus:border-primary focus:ring-1 focus:ring-primary
                                           appearance-none text-body-lg"
                                    required>
                                <option value="" disabled selected>Pilih Jenis Izin</option>
                                <option value="sakit"      @selected(old('jenis') === 'sakit')>Sakit</option>
                                <option value="izin"       @selected(old('jenis') === 'izin')>Izin</option>
                                <option value="dispensasi" @selected(old('jenis') === 'dispensasi')>Dispensasi</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2
                                         pointer-events-none text-on-surface-variant">expand_more</span>
                        </div>
                        @error('jenis')<p class="text-body-sm text-error">{{ $message }}</p>@enderror
                    </div>

                    {{-- Tanggal --}}
                    <div class="space-y-xs">
                        <label for="tanggal" class="text-label-md text-on-surface-variant block">
                            Tanggal Izin
                        </label>
                        <input id="tanggal" type="date" name="tanggal"
                               value="{{ old('tanggal') }}"
                               class="w-full h-touch-target px-md rounded-lg border border-outline-variant
                                      bg-surface focus:border-primary focus:ring-1 focus:ring-primary
                                      text-body-lg"
                               required>
                        @error('tanggal')<p class="text-body-sm text-error">{{ $message }}</p>@enderror
                    </div>

                    {{-- Alasan --}}
                    <div class="space-y-xs">
                        <label for="alasan" class="text-label-md text-on-surface-variant block">
                            Alasan
                        </label>
                        <textarea id="alasan" name="alasan" rows="4"
                                  placeholder="Jelaskan alasan izin secara detail..."
                                  class="w-full p-md rounded-lg border border-outline-variant bg-surface
                                         focus:border-primary focus:ring-1 focus:ring-primary
                                         text-body-lg resize-none"
                                  required>{{ old('alasan') }}</textarea>
                        @error('alasan')<p class="text-body-sm text-error">{{ $message }}</p>@enderror
                    </div>

                    {{-- Lampiran --}}
                    <div class="space-y-xs">
                        <label class="text-label-md text-on-surface-variant block">
                            Lampiran (Bukti Pendukung)
                        </label>
                        <div class="border-2 border-dashed border-outline-variant rounded-xl p-lg
                                    flex flex-col items-center justify-center bg-surface-container-lowest
                                    hover:bg-surface-container-low transition-colors cursor-pointer relative"
                             :class="fileName ? 'border-primary bg-primary-container/10' : ''">
                            <input type="file" name="lampiran"
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="absolute inset-0 opacity-0 cursor-pointer"
                                   @change="fileName = $event.target.files[0]?.name">
                            <span class="material-symbols-outlined text-primary text-4xl mb-sm">cloud_upload</span>
                            <p class="text-body-sm text-on-surface-variant text-center"
                               x-text="fileName || 'Klik atau tarik file ke sini untuk upload surat dokter atau bukti lainnya.'">
                            </p>
                            <p class="text-[10px] text-outline mt-xs">PDF, JPG, PNG (Maks. 5MB)</p>
                        </div>
                        @error('lampiran')<p class="text-body-sm text-error">{{ $message }}</p>@enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full h-touch-target bg-primary text-on-primary font-bold rounded-lg
                                   flex items-center justify-center gap-sm active:scale-95 transition-transform shadow-md"
                            :disabled="submitting">
                        <span class="material-symbols-outlined" x-show="!submitting"
                              style="font-variation-settings:'FILL' 1">send</span>
                        <span class="material-symbols-outlined animate-spin" x-show="submitting">progress_activity</span>
                        <span x-text="submitting ? 'Mengirim...' : 'Kirim Pengajuan'">Kirim Pengajuan</span>
                    </button>
                </form>
            </div>
        </section>

        {{-- ── Recent Permissions ────────────────────────────────── --}}
        <section class="lg:col-span-5 space-y-md">
            <div class="flex items-center justify-between">
                <h3 class="text-headline-md font-semibold text-on-surface">Riwayat Izin Terbaru</h3>
                <a href="{{ route('permissions.index') }}" class="text-primary text-label-md hover:underline">
                    Lihat Semua
                </a>
            </div>

            @forelse(auth()->user()->permissions()->latest()->take(5)->get() as $perm)
            <div class="bg-surface rounded-xl border border-outline-variant p-md flex flex-col gap-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-sm">
                        {{-- Icon per type --}}
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            {{ $perm->jenis === 'sakit' ? 'bg-secondary-container text-on-secondary-container' :
                               ($perm->jenis === 'dispensasi' ? 'bg-primary-container text-on-primary-container' : 'bg-tertiary-container text-on-tertiary-container') }}">
                            <span class="material-symbols-outlined">
                                {{ $perm->jenis === 'sakit' ? 'medical_services' : ($perm->jenis === 'dispensasi' ? 'event_available' : 'info') }}
                            </span>
                        </div>
                        <div>
                            <p class="text-body-lg font-semibold text-on-surface">{{ $perm->jenis_label }}</p>
                            <p class="text-body-sm text-outline">{{ $perm->tanggal->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                    {{-- Status badge --}}
                    @php
                        $badgeClass = match($perm->status) {
                            'disetujui' => 'bg-primary-container text-on-primary-container',
                            'ditolak'   => 'bg-error-container text-on-error-container',
                            default     => 'bg-secondary-container text-on-secondary-container',
                        };
                        $dotClass = match($perm->status) {
                            'disetujui' => 'bg-primary',
                            'ditolak'   => 'bg-error',
                            default     => 'bg-secondary',
                        };
                    @endphp
                    <span class="px-sm py-1 rounded-full {{ $badgeClass }} text-status-badge font-bold flex items-center gap-xs whitespace-nowrap">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                        {{ $perm->status_label }}
                    </span>
                </div>
                <p class="text-body-sm text-on-surface-variant line-clamp-2">"{{ $perm->alasan }}"</p>
            </div>
            @empty
            <div class="bg-surface rounded-xl border border-outline-variant p-lg text-center text-body-sm text-on-surface-variant">
                Belum ada riwayat izin.
            </div>
            @endforelse
        </section>

    </div>
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="permission" role="siswa" />
@endsection

@push('scripts')
<script>
function permissionForm() {
    return {
        fileName: '',
        submitting: false,
    };
}
</script>
@endpush
