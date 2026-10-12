@extends('layouts.app')
@section('title', 'Upload Laporan Kegiatan')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6"
     style="max-width: 760px;"
     x-data="uploadForm()">

    {{-- ── Hero Header ─────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden shadow-lg"
             style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 55%, #3B82F6 100%); border-radius: 24px; padding: 28px 24px;">
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="border-radius: 24px;">
            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full" style="background:rgba(255,255,255,0.08); filter: blur(20px);"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full" style="background:rgba(255,255,255,0.06); filter: blur(16px);"></div>
        </div>

        <div class="relative z-10 flex items-center justify-between gap-4">
            <div>
                <p class="text-white/80 text-[12px] font-bold uppercase tracking-wider mb-1">Dokumentasi Magang</p>
                <h1 class="text-white font-extrabold text-[22px] sm:text-[26px] leading-tight tracking-tight">
                    Upload Laporan
                </h1>
                <p class="text-white/80 text-[13px] font-medium mt-1">Dokumentasikan kegiatan magang hari ini</p>
            </div>
            <a href="{{ route('activity-reports.index') }}"
               class="flex-shrink-0 flex items-center gap-1.5 rounded-2xl px-4 py-2.5 font-bold text-[13px]
                      active:scale-95 transition-all duration-150 text-white hover:bg-white/30"
               style="background: rgba(255,255,255,0.20); border: 1px solid rgba(255,255,255,0.38); backdrop-filter: blur(10px);">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
        </div>
    </section>

    {{-- Error validation --}}
    @if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-2xl p-4">
        <span class="material-symbols-outlined text-red-600 text-[20px] flex-shrink-0 mt-0.5"
              style="font-variation-settings:'FILL' 1">error</span>
        <div class="text-[13px] text-red-800 font-medium leading-relaxed">
            <p class="font-bold">Mohon perbaiki kesalahan berikut:</p>
            <ul class="list-disc list-inside mt-1 space-y-0.5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- ── Form Card ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-200/80 bg-slate-50">
            <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-[18px]"
                      style="font-variation-settings:'FILL' 1">edit_note</span>
            </div>
            <div>
                <p class="text-[14px] font-bold text-slate-800">Form Laporan</p>
                <p class="text-[11px] text-slate-500">Isi semua kolom yang diperlukan</p>
            </div>
        </div>

        <form method="POST" action="{{ route('activity-reports.store') }}"
              enctype="multipart/form-data"
              @submit="submitting = true"
              class="p-6 space-y-6">
            @csrf

            {{-- Tanggal Kegiatan --}}
            <div>
                <label for="tanggal"
                       class="block text-[12px] font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Tanggal Kegiatan <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute text-slate-400 pointer-events-none"
                          style="left:16px; top:50%; transform:translateY(-50%); font-size:20px; line-height:1;">
                        calendar_today
                    </span>
                    <input id="tanggal" type="date" name="tanggal"
                           value="{{ old('tanggal', today()->format('Y-m-d')) }}"
                           max="{{ today()->format('Y-m-d') }}"
                           style="padding-left:48px; padding-right:16px;"
                           class="w-full h-12 rounded-xl border border-slate-300
                                  bg-white focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20
                                  text-[14px] font-semibold text-slate-900 transition-all outline-none"
                           required>
                </div>
                @error('tanggal')
                <p class="mt-1.5 text-[12px] text-red-500 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">error</span>
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Judul Laporan --}}
            <div>
                <label for="judul"
                       class="block text-[12px] font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Judul Laporan <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute text-slate-400 pointer-events-none"
                          style="left:16px; top:50%; transform:translateY(-50%); font-size:20px; line-height:1;">
                        title
                    </span>
                    <input id="judul" type="text" name="judul"
                           value="{{ old('judul') }}"
                           placeholder="Contoh: Membuat desain UI halaman login"
                           style="padding-left:48px; padding-right:16px;"
                           class="w-full h-12 rounded-xl border border-slate-300
                                  bg-white focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20
                                  text-[14px] text-slate-900 transition-all outline-none"
                           required>
                </div>
                @error('judul')
                <p class="mt-1.5 text-[12px] text-red-500 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">error</span>
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi"
                       class="block text-[12px] font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Deskripsi
                    <span class="text-slate-400 font-semibold normal-case tracking-normal ml-1">(Opsional)</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                          placeholder="Jelaskan kegiatan yang dilakukan hari ini..."
                          class="w-full p-4 rounded-xl border border-slate-300
                                 bg-white focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20
                                 text-[14px] text-slate-900 resize-none transition-all outline-none leading-relaxed">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                <p class="mt-1.5 text-[12px] text-red-500 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">error</span>
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Upload File --}}
            <div>
                <label class="block text-[12px] font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    File Dokumentasi
                    <span class="text-slate-400 font-semibold normal-case tracking-normal ml-1">(Opsional, maks. 10 file, 50MB/file)</span>
                </label>

                {{-- Drop zone --}}
                <div class="relative border-2 border-dashed rounded-2xl transition-all duration-150 cursor-pointer overflow-hidden"
                     :class="files.length
                         ? 'border-[#2563EB] bg-blue-50/40'
                         : 'border-slate-300 bg-slate-50/50 hover:bg-slate-50 hover:border-slate-400'"
                     @dragover.prevent @drop.prevent="handleDrop($event)">
                    <input type="file" name="files[]" multiple x-ref="fileInput"
                           accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                           class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                           @change="handleFiles($event)">

                    <div class="flex flex-col items-center justify-center py-6 px-4 text-center">
                        <div class="w-12 h-12 rounded-2xl mb-2 flex items-center justify-center transition-colors"
                             :class="files.length ? 'bg-primary/10 text-primary' : 'bg-slate-100 text-slate-400'">
                            <span class="material-symbols-outlined text-[24px]"
                                  style="font-variation-settings:'FILL' 1">cloud_upload</span>
                        </div>
                        <p class="text-[13px] font-bold"
                           :class="files.length ? 'text-[#2563EB]' : 'text-slate-700'">
                            <span x-show="!files.length">Tap untuk pilih atau drag & drop file</span>
                            <span x-show="files.length" x-text="files.length + ' file dipilih'"></span>
                        </p>
                        <p class="text-[11px] text-slate-400 mt-1">
                            Foto, Video, PDF, Word, Excel, PowerPoint
                        </p>
                    </div>
                </div>

                {{-- Preview file --}}
                <div x-show="files.length > 0" class="space-y-2 mt-3">
                    <template x-for="(file, index) in files" :key="index">
                        <div class="flex items-center gap-3 bg-slate-50 rounded-xl px-4 py-2.5
                                    border border-slate-200">
                            {{-- Preview thumbnail gambar --}}
                            <template x-if="file.type.startsWith('image/')">
                                <img :src="getPreviewUrl(file)"
                                     class="w-10 h-10 rounded-xl object-cover flex-shrink-0 border border-slate-200">
                            </template>
                            {{-- Ikon untuk non-gambar --}}
                            <template x-if="!file.type.startsWith('image/')">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-primary text-[20px]"
                                          style="font-variation-settings:'FILL' 1"
                                          x-text="getFileIcon(file.type)"></span>
                                </div>
                            </template>

                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-semibold text-slate-800 truncate" x-text="file.name"></p>
                                <p class="text-[11px] text-slate-400" x-text="formatSize(file.size)"></p>
                            </div>

                            <button type="button" @click="removeFile(index)"
                                    class="w-7 h-7 rounded-lg flex items-center justify-center
                                           text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full h-12 rounded-xl font-extrabold text-[14px] text-white
                           flex items-center justify-center gap-2
                           transition-all duration-200 active:scale-[0.98]
                           disabled:opacity-50 disabled:cursor-not-allowed"
                    style="background: #2563EB; box-shadow: 0 4px 16px rgba(37,99,235,0.30);"
                    :disabled="submitting">
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
            const maxPerFile = 50 * 1024 * 1024; // 50MB
            const available  = 10 - this.files.length;

            for (const f of newFiles.slice(0, available)) {
                if (f.size > maxPerFile) {
                    alert(`File "${f.name}" terlalu besar (${this.formatSize(f.size)}). Maksimal ukuran file adalah 50 MB.`);
                    continue;
                }
                this.files.push(f);
            }
            this.syncInput();
        },

        removeFile(index) {
            this.files.splice(index, 1);
            this.syncInput();
        },

        syncInput() {
            if (this.$refs.fileInput) {
                const dt = new DataTransfer();
                this.files.forEach(f => dt.items.add(f));
                this.$refs.fileInput.files = dt.files;
            }
        },

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
