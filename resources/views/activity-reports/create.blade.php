@extends('layouts.app')
@section('title', 'Upload Laporan Kegiatan')

@section('content')
<div class="px-md max-w-lg mx-auto pt-lg pb-4" x-data="uploadForm()">

    {{-- Header --}}
    <section class="mb-lg">
        <div class="flex items-center gap-sm mb-xs">
            <a href="{{ route('activity-reports.index') }}"
               class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors">
                arrow_back
            </a>
            <h2 class="text-headline-lg-mobile font-bold text-on-surface">Upload Laporan Kegiatan</h2>
        </div>
        <p class="text-body-sm text-on-surface-variant ml-9">
            Dokumentasikan kegiatan magang hari ini
        </p>
    </section>

    @if ($errors->any())
        <div class="mb-lg p-md bg-error-container text-on-error-container rounded-xl text-body-sm space-y-xs">
            @foreach ($errors->all() as $error)
                <p class="flex items-center gap-sm"><span class="material-symbols-outlined text-[16px]">error</span>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('activity-reports.store') }}"
          enctype="multipart/form-data" class="space-y-md">
        @csrf

        {{-- Tanggal --}}
        <div class="space-y-xs">
            <label for="tanggal" class="text-label-md text-on-surface-variant block">Tanggal Kegiatan</label>
            <input id="tanggal" type="date" name="tanggal"
                   value="{{ old('tanggal', today()->format('Y-m-d')) }}"
                   max="{{ today()->format('Y-m-d') }}"
                   class="w-full h-touch-target px-md rounded-xl border border-outline-variant
                          bg-surface focus:border-primary focus:ring-1 focus:ring-primary
                          outline-none text-body-lg"
                   required>
        </div>

        {{-- Judul --}}
        <div class="space-y-xs">
            <label for="judul" class="text-label-md text-on-surface-variant block">Judul Laporan</label>
            <input id="judul" type="text" name="judul"
                   value="{{ old('judul') }}"
                   placeholder="Contoh: Membuat desain UI halaman login"
                   class="w-full h-touch-target px-md rounded-xl border border-outline-variant
                          bg-surface focus:border-primary focus:ring-1 focus:ring-primary
                          outline-none text-body-lg"
                   required>
        </div>

        {{-- Deskripsi --}}
        <div class="space-y-xs">
            <label for="deskripsi" class="text-label-md text-on-surface-variant block">
                Deskripsi Kegiatan
                <span class="text-outline font-normal">(opsional)</span>
            </label>
            <textarea id="deskripsi" name="deskripsi" rows="4"
                      placeholder="Jelaskan kegiatan yang dilakukan hari ini..."
                      class="w-full p-md rounded-xl border border-outline-variant bg-surface
                             focus:border-primary focus:ring-1 focus:ring-primary
                             outline-none text-body-lg resize-none">{{ old('deskripsi') }}</textarea>
        </div>

        {{-- Upload File --}}
        <div class="space-y-xs">
            <label class="text-label-md text-on-surface-variant block">
                File Dokumentasi
                <span class="text-outline font-normal">(maks. 10 file, 50MB/file)</span>
            </label>

            {{-- Drop zone --}}
            <div class="border-2 border-dashed border-outline-variant rounded-xl p-lg
                        flex flex-col items-center justify-center text-center relative
                        hover:border-primary hover:bg-surface-container-low transition-colors cursor-pointer"
                 :class="files.length ? 'border-primary bg-primary-container/10' : ''"
                 @dragover.prevent @drop.prevent="handleDrop($event)">
                <input type="file" name="files[]" multiple
                       accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                       class="absolute inset-0 opacity-0 cursor-pointer"
                       @change="handleFiles($event)">
                <span class="material-symbols-outlined text-primary text-5xl mb-sm"
                      style="font-variation-settings:'FILL' 1">cloud_upload</span>
                <p class="text-body-sm font-semibold text-on-surface">
                    Tap untuk pilih atau drag & drop
                </p>
                <p class="text-label-md text-outline mt-xs">
                    Foto, Video, PDF, Word, Excel, PowerPoint
                </p>
            </div>

            {{-- Preview file yang dipilih --}}
            <div x-show="files.length > 0" class="space-y-sm mt-sm">
                <p class="text-label-md text-on-surface-variant uppercase tracking-wider">
                    File dipilih (<span x-text="files.length"></span>)
                </p>
                <template x-for="(file, index) in files" :key="index">
                    <div class="flex items-center gap-sm bg-surface-container-low rounded-xl px-md py-sm">
                        {{-- Ikon berdasarkan tipe --}}
                        <span class="material-symbols-outlined text-primary shrink-0"
                              :style="'font-variation-settings: FILL 1'"
                              x-text="getFileIcon(file.type)"></span>
                        <div class="flex-1 min-w-0">
                            <p class="text-body-sm font-semibold text-on-surface truncate" x-text="file.name"></p>
                            <p class="text-label-md text-outline" x-text="formatSize(file.size)"></p>
                        </div>
                        {{-- Preview thumbnail untuk gambar --}}
                        <template x-if="file.type.startsWith('image/')">
                            <img :src="getPreviewUrl(file)"
                                 class="w-10 h-10 rounded-lg object-cover shrink-0 border border-outline-variant">
                        </template>
                        <template x-if="file.type.startsWith('video/')">
                            <span class="material-symbols-outlined text-secondary text-[32px] shrink-0"
                                  style="font-variation-settings:'FILL' 1">play_circle</span>
                        </template>
                        <button type="button" @click="removeFile(index)"
                                class="material-symbols-outlined text-outline hover:text-error
                                       transition-colors shrink-0">
                            close
                        </button>
                    </div>
                </template>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full h-touch-target bg-primary text-on-primary font-bold rounded-xl
                       flex items-center justify-center gap-sm active:scale-95 transition-transform shadow-md"
                :disabled="files.length === 0 || submitting">
            <span class="material-symbols-outlined text-[18px]" x-show="!submitting"
                  style="font-variation-settings:'FILL' 1">upload</span>
            <span class="material-symbols-outlined animate-spin text-[18px]" x-show="submitting">progress_activity</span>
            <span x-text="submitting ? 'Mengunggah...' : 'Kirim Laporan'">Kirim Laporan</span>
        </button>

    </form>

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

        handleFiles(e) {
            this.addFiles([...e.target.files]);
        },

        handleDrop(e) {
            this.addFiles([...e.dataTransfer.files]);
        },

        addFiles(newFiles) {
            const MAX = 10;
            const available = MAX - this.files.length;
            newFiles.slice(0, available).forEach(f => this.files.push(f));
        },

        removeFile(index) {
            this.files.splice(index, 1);
        },

        getPreviewUrl(file) {
            if (!this.previews[file.name]) {
                this.previews[file.name] = URL.createObjectURL(file);
            }
            return this.previews[file.name];
        },

        getFileIcon(mime) {
            if (mime.startsWith('image/')) return 'image';
            if (mime.startsWith('video/')) return 'videocam';
            if (mime.includes('pdf'))      return 'picture_as_pdf';
            if (mime.includes('word') || mime.includes('document')) return 'description';
            if (mime.includes('sheet') || mime.includes('excel'))   return 'table_chart';
            if (mime.includes('presentation') || mime.includes('powerpoint')) return 'slideshow';
            return 'attach_file';
        },

        formatSize(bytes) {
            if (bytes < 1024)       return bytes + ' B';
            if (bytes < 1048576)    return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        },
    };
}
</script>
@endpush
