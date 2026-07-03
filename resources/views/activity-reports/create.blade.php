@extends('layouts.app')
@section('title', 'Upload Laporan Kegiatan')

@section('content')
<div class="max-w-lg mx-auto" x-data="uploadForm()">

    {{-- ── Hero Header ─────────────────────────────────────────────── --}}
    <section class="relative bg-primary overflow-hidden px-md pt-lg pb-lg"
             style="border-radius: 0 0 32px 32px;">
        <div class="pointer-events-none absolute -top-10 -right-10 w-44 h-44 rounded-full"
             style="background: rgba(255,255,255,0.07)"></div>
        <div class="pointer-events-none absolute top-6 -right-2 w-24 h-24 rounded-full"
             style="background: rgba(255,255,255,0.05)"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-sm mb-2">
                <a href="{{ route('activity-reports.index') }}"
                   class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors"
                   style="background: rgba(255,255,255,0.15)">
                    <span class="material-symbols-outlined text-white text-[18px]">arrow_back</span>
                </a>
                <p class="text-white/60 text-[11px] font-semibold uppercase tracking-widest">
                    Dokumentasi Magang
                </p>
            </div>
            <h2 class="text-white font-extrabold"
                style="font-size: 24px; line-height: 30px; letter-spacing: -0.02em;">
                Upload Laporan
            </h2>
            <p class="text-white/60 text-[13px] mt-1">Dokumentasikan kegiatan magang hari ini</p>
        </div>
    </section>

    <div class="px-md pt-lg pb-xl space-y-lg">

        {{-- Error validation --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-2xl p-md">
            <div class="flex items-center gap-sm mb-sm">
                <span class="material-symbols-outlined text-red-500 text-[18px]"
                      style="font-variation-settings:'FILL' 1">error</span>
                <p class="text-[13px] font-bold text-red-700">Mohon perbaiki kesalahan berikut:</p>
            </div>
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                <li class="text-[12px] text-red-600 flex items-start gap-1.5">
                    <span class="w-1 h-1 rounded-full bg-red-400 mt-1.5 flex-shrink-0"></span>
                    {{ $error }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- ── Form Card ─────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-outline-variant/50 shadow-sm overflow-hidden">

            <div class="flex items-center gap-sm px-md py-md border-b border-outline-variant/40">
                <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-[18px]"
                          style="font-variation-settings:'FILL' 1">edit_note</span>
                </div>
                <div>
                    <p class="text-[14px] font-bold text-on-surface">Form Laporan</p>
                    <p class="text-[11px] text-on-surface-variant">Isi semua kolom yang diperlukan</p>
                </div>
            </div>

            <form method="POST" action="{{ route('activity-reports.store') }}"
                  enctype="multipart/form-data"
                  class="p-md space-y-md">
                @csrf

                {{-- Tanggal Kegiatan --}}
                <div>
                    <label for="tanggal"
                           class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Tanggal Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">
                            calendar_today
                        </span>
                        <input id="tanggal" type="date" name="tanggal"
                               value="{{ old('tanggal', today()->format('Y-m-d')) }}"
                               max="{{ today()->format('Y-m-d') }}"
                               class="w-full h-touch-target pl-10 pr-md rounded-2xl border border-outline-variant/60
                                      bg-surface-container/30 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20
                                      text-[14px] font-semibold text-on-surface transition-all duration-150 outline-none"
                               required>
                    </div>
                </div>

                {{-- Judul Laporan --}}
                <div>
                    <label for="judul"
                           class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Judul Laporan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">
                            title
                        </span>
                        <input id="judul" type="text" name="judul"
                               value="{{ old('judul') }}"
                               placeholder="Contoh: Membuat desain UI halaman login"
                               class="w-full h-touch-target pl-10 pr-md rounded-2xl border border-outline-variant/60
                                      bg-surface-container/30 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20
                                      text-[14px] text-on-surface transition-all duration-150 outline-none"
                               required>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi"
                           class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Deskripsi
                        <span class="text-outline font-semibold normal-case tracking-normal ml-1">(Opsional)</span>
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                              placeholder="Jelaskan kegiatan yang dilakukan hari ini..."
                              class="w-full p-md rounded-2xl border border-outline-variant/60
                                     bg-surface-container/30 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20
                                     text-[14px] text-on-surface resize-none transition-all duration-150 outline-none leading-relaxed">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Upload File --}}
                <div>
                    <label class="block text-[12px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        File Dokumentasi <span class="text-red-500">*</span>
                        <span class="text-outline font-semibold normal-case tracking-normal ml-1">(maks. 10 file, 50MB/file)</span>
                    </label>

                    {{-- Drop zone --}}
                    <div class="relative border-2 border-dashed rounded-2xl transition-all duration-150 cursor-pointer overflow-hidden"
                         :class="files.length
                             ? 'border-primary bg-primary/5'
                             : 'border-outline-variant/60 bg-surface-container/30 hover:bg-surface-container hover:border-outline'"
                         @dragover.prevent @drop.prevent="handleDrop($event)">
                        <input type="file" name="files[]" multiple
                               accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                               class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                               @change="handleFiles($event)">

                        <div class="flex flex-col items-center justify-center py-lg px-md text-center">
                            <div class="w-14 h-14 rounded-2xl mb-sm flex items-center justify-center"
                                 :class="files.length ? 'bg-primary/10' : 'bg-surface-container'"
                                 style="transition: all 0.15s">
                                <span class="material-symbols-outlined text-[28px]"
                                      :class="files.length ? 'text-primary' : 'text-outline'"
                                      style="font-variation-settings:'FILL' 1">cloud_upload</span>
                            </div>
                            <p class="text-[13px] font-bold"
                               :class="files.length ? 'text-primary' : 'text-on-surface-variant'">
                                <span x-show="!files.length">Tap untuk pilih atau drag & drop</span>
                                <span x-show="files.length" x-text="files.length + ' file dipilih'"></span>
                            </p>
                            <p class="text-[11px] text-outline mt-1">
                                Foto, Video, PDF, Word, Excel, PowerPoint
                            </p>
                        </div>
                    </div>

                    {{-- Preview file --}}
                    <div x-show="files.length > 0" class="space-y-sm mt-sm">
                        <template x-for="(file, index) in files" :key="index">
                            <div class="flex items-center gap-sm bg-surface-container/50 rounded-2xl px-md py-sm
                                        border border-outline-variant/40">
                                {{-- Preview thumbnail gambar --}}
                                <template x-if="file.type.startsWith('image/')">
                                    <img :src="getPreviewUrl(file)"
                                         class="w-10 h-10 rounded-xl object-cover flex-shrink-0 border border-outline-variant/30">
                                </template>
                                {{-- Ikon untuk non-gambar --}}
                                <template x-if="!file.type.startsWith('image/')">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-primary text-[18px]"
                                              style="font-variation-settings:'FILL' 1"
                                              x-text="getFileIcon(file.type)"></span>
                                    </div>
                                </template>

                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-semibold text-on-surface truncate" x-text="file.name"></p>
                                    <p class="text-[11px] text-outline" x-text="formatSize(file.size)"></p>
                                </div>

                                <button type="button" @click="removeFile(index)"
                                        class="w-7 h-7 rounded-lg flex items-center justify-center
                                               text-outline active:bg-red-50 active:text-red-500 transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full h-touch-target rounded-2xl font-extrabold text-[14px] text-white
                               flex items-center justify-center gap-sm
                               transition-all duration-200 active:scale-[0.98]
                               disabled:opacity-50 disabled:cursor-not-allowed"
                        style="background: #006c49; box-shadow: 0 6px 24px rgba(0,108,73,0.35);"
                        :disabled="files.length === 0 || submitting"
                        @click="submitting = true">
                    <span class="material-symbols-outlined text-[18px]"
                          x-show="!submitting"
                          style="font-variation-settings:'FILL' 1">upload</span>
                    <span class="material-symbols-outlined text-[18px] animate-spin"
                          x-show="submitting">progress_activity</span>
                    <span x-text="submitting ? 'Mengunggah...' : 'Kirim Laporan'">Kirim Laporan</span>
                </button>

            </form>
        </div>

    </div>
</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="activity" role="siswa" />
@endsection

@push('scripts')
<script>
function uploadForm() {
    return {
        files:      [],
        submitting: false,
        previews:   {},

        handleFiles(e) { this.addFiles([...e.target.files]); },
        handleDrop(e)  { this.addFiles([...e.dataTransfer.files]); },

        addFiles(newFiles) {
            const available = 10 - this.files.length;
            newFiles.slice(0, available).forEach(f => this.files.push(f));
        },

        removeFile(index) { this.files.splice(index, 1); },

        getPreviewUrl(file) {
            if (!this.previews[file.name]) {
                this.previews[file.name] = URL.createObjectURL(file);
            }
            return this.previews[file.name];
        },

        getFileIcon(mime) {
            if (mime.startsWith('video/'))                              return 'videocam';
            if (mime.includes('pdf'))                                  return 'picture_as_pdf';
            if (mime.includes('word') || mime.includes('document'))    return 'description';
            if (mime.includes('sheet') || mime.includes('excel'))      return 'table_chart';
            if (mime.includes('presentation') || mime.includes('powerpoint')) return 'slideshow';
            return 'attach_file';
        },

        formatSize(bytes) {
            if (bytes < 1024)    return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        },
    };
}
</script>
@endpush
